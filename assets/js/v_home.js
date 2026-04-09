// Tailwind Configuration
tailwind.config = {
	theme: {
		extend: {
			colors: {
				gold: {
					300: "#f5d98b",
					400: "#e8b84b",
					500: "#d4a017",
					600: "#b8860b",
				},
				onyx: "#0a0a0a",
				obsidian: "#111111",
				graphite: "#1a1a1a",
				smoke: "#888",
				ash: "#555",
			},
			fontFamily: {
				display: ["Cormorant Garamond", "serif"],
				sans: ["DM Sans", "sans-serif"],
				title: ["Syne", "sans-serif"],
			},
		},
	},
};

// Mobile Menu Toggle
function toggleMobileMenu() {
	const m = document.getElementById("mobile-menu");
	m.classList.toggle("hidden");
	m.classList.toggle("flex");
}

// Add Item Row
function addRow() {
	const tbody = document.getElementById("items-body");
	const tr = document.createElement("tr");
	tr.innerHTML = `
        <td style="padding:4px 12px 4px 0; width:120px;">
          <select class="form-input">
            <option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option>
          </select>
        </td>
        <td style="padding:4px 12px 4px 0;">
          <input type="number" class="form-input qty-input" placeholder="0" min="1" oninput="calcDuration()" />
        </td>
        <td style="padding:4px 0; vertical-align:middle;">
          <button type="button" onclick="removeRow(this)" class="remove-btn">✕</button>
        </td>
      `;
	tbody.appendChild(tr);
}

// Remove Item Row
function removeRow(btn) {
	const tbody = document.getElementById("items-body");
	if (tbody.rows.length > 1) {
		btn.closest("tr").remove();
		calcDuration();
	}
}

// Calculate Production Duration
let lastEstDuration = 0;
let earliestSafeDate = "";
let isCalculatingSafeDate = false;

function formatDuration(mins) {
	if (mins <= 0) return "0 mins";
	const d = Math.floor(mins / 510);
	const rem = mins % 510;
	const h = Math.floor(rem / 60);
	const m = rem % 60;
	let parts = [];
	if (d > 0) parts.push(d + "d");
	if (h > 0) parts.push(h + "h");
	if (m > 0) parts.push(m + "m");
	return parts.join(" ");
}

function calcDuration() {
	let qtyInputs = document.querySelectorAll(".qty-input");
	let total = 0;

	qtyInputs.forEach((input) => {
		total += parseInt(input.value) || 0;
	});

	let base = parseInt(document.getElementById("product-type").value) || 10;
	let setupTime = 30;
	let est = total > 0 ? total * base + setupTime : 0;

	if (total > 0) {
		document.getElementById("duration-box").style.display = "block";
		document.getElementById("duration-val").innerText = formatDuration(est);
		document.getElementById(
			"duration-note"
		).innerText = `Waktu pengerjaan ${formatDuration(
			est
		)} (Termasuk setup ${setupTime} menit)`;
		document.getElementById("est-duration-hidden").value = est;
	} else {
		document.getElementById("duration-box").style.display = "none";
		document.getElementById("est-duration-hidden").value = 0;
	}

	if (Math.abs(est - lastEstDuration) > 1 || (est > 0 && !earliestSafeDate)) {
		lastEstDuration = est;
		fetchSafeDeadline(est);
	} else if (est === 0) {
		earliestSafeDate = "";
		document.getElementById("deadline-hint").style.display = "none";
		document.getElementById("deadline-warning").style.display = "none";
		checkFormValidity();
	}
}

function fetchSafeDeadline(mins) {
	if (mins <= 0) return;

	isCalculatingSafeDate = true;
	checkFormValidity();

	const hint = document.getElementById("deadline-hint");
	hint.textContent = "Calculating earliest safe deadline...";
	hint.style.display = "block";
	hint.style.color = "#e8b84b"; // gold-400

	fetch(DEADLINE_URL + "?est_duration=" + mins)
		.then((r) => r.json())
		.then((data) => {
			isCalculatingSafeDate = false;
			if (data.earliest_date) {
				earliestSafeDate = data.earliest_date;
				const input = document.getElementById("deadline-input");
				input.min = earliestSafeDate;

				hint.textContent = "Based on queue, earliest safe deadline is " + earliestSafeDate;
				hint.style.color = "#888"; // smoke
				hint.style.display = "block";

				checkDeadline();
			} else {
				hint.style.display = "none";
				checkFormValidity();
			}
		})
		.catch(() => {
			isCalculatingSafeDate = false;
			hint.style.display = "none";
			checkFormValidity();
		});
}

function checkDeadline() {
	const input = document.getElementById("deadline-input").value;
	const warn = document.getElementById("deadline-warning");

	if (input && earliestSafeDate && input < earliestSafeDate) {
		warn.innerHTML = `<b>Terlalu Cepat!</b> Sistem memperkirakan waktu selesai sekitar ${earliestSafeDate}. Menggunakan tanggal ini berisiko keterlambatan.`;
		warn.style.display = "block";
	} else {
		warn.style.display = "none";
	}
	checkFormValidity();
}

function checkFormValidity() {
	const name = document.getElementsByName("customer_name")[0].value.trim();
	const phone = document.getElementsByName("phone")[0].value.trim();
	const input = document.getElementById("deadline-input").value;
	const est = parseInt(document.getElementById("est-duration-hidden").value) || 0;

	const isDeadlineInvalid = input && earliestSafeDate && input < earliestSafeDate;
	const isWaitingForDate = est > 0 && isCalculatingSafeDate;
	const hasNoDate = est > 0 && !input;
	const hasNoContact = !name || !phone;

	const btn = document.getElementById("submit-btn");

	if (input && !isDeadlineInvalid && !isWaitingForDate && !hasNoDate && !hasNoContact) {
		btn.disabled = false;
		btn.style.opacity = "1";
		btn.style.cursor = "pointer";
	} else {
		btn.disabled = true;
		btn.style.opacity = "0.4";
		btn.style.cursor = "not-allowed";
	}
}

// Submit Form
function submitForm() {
	// Let the form submit normally, modal can be shown after redirect or via AJAX success
	// For now, removing the direct modal show to allow backend processing
	// document.getElementById("success-modal").style.display = "flex";
}

// Close Modals
function closeModal() {
	document.getElementById("success-modal").style.display = "none";
}

function closeErrorModal() {
	document.getElementById("error-modal").style.display = "none";
}

// Clear Form
function clearForm() {
	document.querySelectorAll(".qty-input").forEach((i) => (i.value = ""));
	document.getElementById("duration-box").style.display = "none";
	earliestSafeDate = "";
    document.getElementById("deadline-hint").style.display = "none";
    document.getElementById("deadline-warning").style.display = "none";
    checkFormValidity();
}

// Initialize Deadline
document.addEventListener("DOMContentLoaded", function () {
    // Add event listeners for contact info to trigger validity check
    document.getElementsByName("customer_name")[0].addEventListener("input", checkFormValidity);
    document.getElementsByName("phone")[0].addEventListener("input", checkFormValidity);

    // Handle alert parameters
    const urlParams = new URLSearchParams(window.location.search);
    const alertType = urlParams.get('alert');
    
    if (alertType === 'success') {
        document.getElementById("success-modal").style.display = "flex";
    } else if (alertType === 'deadline_conflict' || alertType === 'save_failed' || alertType === 'gagal' || alertType === 'error') {
        let title = "Gagal Mengirim!";
        let msg = "Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.";
        
        if (alertType === 'deadline_conflict') {
            title = "Waktu Tidak Mencukupi";
            msg = "Sistem memperkirakan waktu pengerjaan tidak cukup untuk deadline yang Anda pilih. Silakan pilih tanggal yang lebih lambat.";
        } else if (alertType === 'save_failed') {
            title = "Kesalahan Sistem";
            msg = "Gagal menyimpan data pesanan Anda ke database. Silakan hubungi admin via WhatsApp.";
        }
        
        document.getElementById("error-modal-title").innerText = title;
        document.getElementById("error-modal-msg").innerText = msg;
        document.getElementById("error-modal").style.display = "flex";
    }
});

