/**
 * Dashboard Scripts
 */

// Duration calculator (Days based)
function calcDurationDays(qty) {
	qty = parseInt(qty);
	const box = document.getElementById("duration-box");
	const val = document.getElementById("duration-val");
	const note = document.getElementById("duration-note");

	if (!qty || qty <= 0) {
		if (box) box.style.display = "none";
		return;
	}

	let days, label;
	if (qty <= 5) {
		days = 1;
		label = "1–5 pcs";
	} else if (qty <= 20) {
		days = 2;
		label = "6–20 pcs";
	} else if (qty <= 50) {
		days = 3;
		label = "21–50 pcs";
	} else if (qty <= 100) {
		days = 5;
		label = "51–100 pcs";
	} else {
		days = 7;
		label = "100+ pcs";
	}

	if (val) val.textContent = days + (days === 1 ? " day" : " days");
	if (note)
		note.textContent = `Range: ${label} → ${days} day${
			days > 1 ? "s" : ""
		}. Actual queue position assigned by SJF.`;
	if (box) box.style.display = "block";
}

// Add Item Row (for New Order)
function addRow() {
	let table = document.getElementById("items");
	if (!table) return;
	let row = table.insertRow();

	row.innerHTML = `
    <td>
      <select name="size[]" class="form-input">
        <option>S</option>
        <option>M</option>
        <option>L</option>
        <option>XL</option>
      </select>
    </td>
    <td>
      <input type="number" name="qty[]" class="form-input qty-input" oninput="calcDuration()" required>
    </td>
    <td>
      <button type="button" onclick="removeRow(this)">X</button>
    </td>
  `;
}

// Remove Item Row
function removeRow(btn) {
	btn.parentNode.parentNode.remove();
	calcDuration();
}

// Calculate Production Duration (Minutes based, for form)
// Specialized versions in v_new_order.php or v_edit_order.php will take precedence.
if (typeof window.calcDuration === "undefined") {
	window.calcDuration = function () {
		let qtyInputs = document.querySelectorAll(".qty-input");
		let total = 0;

		qtyInputs.forEach((input) => {
			total += parseInt(input.value) || 0;
		});

		let productTypeSelect = document.getElementById("product-type");
		if (!productTypeSelect) return;

		let base = parseInt(productTypeSelect.value);
		let duration = total > 0 ? total * base + 30 : 0;

		const box = document.getElementById("duration-box");
		const val = document.getElementById("duration-val");
		const hidden = document.getElementById("est-duration-hidden");

		if (total > 0) {
			if (box) box.style.display = "block";
			if (val) val.innerText = duration + " minutes";
			if (hidden) hidden.value = duration;
		} else {
			if (box) box.style.display = "none";
		}
	};
}
