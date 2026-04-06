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
function calcDuration() {
	let qtyInputs = document.querySelectorAll(".qty-input");
	let total = 0;

	qtyInputs.forEach((input) => {
		total += parseInt(input.value) || 0;
	});

	let base = parseInt(document.getElementById("product-type").value);
	let duration = total * base + 20;

	if (total > 0) {
		document.getElementById("duration-box").style.display = "block";
		document.getElementById("duration-val").innerText = duration + " Menit";
		document.getElementById("duration-note").innerText = `Waktu pengerjaan ${duration} menit (Termasuk setup 20 menit)`;
		document.getElementById("est-duration-hidden").value = duration;
	} else {
		document.getElementById("duration-box").style.display = "none";
	}
}

// Submit Form
function submitForm() {
	document.getElementById("success-modal").style.display = "flex";
}

// Close Modal
function closeModal() {
	document.getElementById("success-modal").style.display = "none";
}

// Clear Form
function clearForm() {
	// Reset qty inputs
	document.querySelectorAll(".qty-input").forEach((i) => (i.value = ""));
	document.getElementById("duration-box").style.display = "none";
}

// Initialize Deadline
document.addEventListener("DOMContentLoaded", function () {
	const deadlineInput = document.getElementById("deadline");
	if (deadlineInput) {
		const today = new Date();
		today.setDate(today.getDate() + 7);
		deadlineInput.min = today.toISOString().split("T")[0];
	}
});
