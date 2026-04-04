</div><!-- end main -->

<script>
	// Duration calculator
	function calcDuration(qty) {
		qty = parseInt(qty);
		const box = document.getElementById('duration-box');
		const val = document.getElementById('duration-val');
		const note = document.getElementById('duration-note');

		if (!qty || qty <= 0) {
			box.style.display = 'none';
			return;
		}

		let days, label;
		if (qty <= 5) {
			days = 1;
			label = '1–5 pcs';
		} else if (qty <= 20) {
			days = 2;
			label = '6–20 pcs';
		} else if (qty <= 50) {
			days = 3;
			label = '21–50 pcs';
		} else if (qty <= 100) {
			days = 5;
			label = '51–100 pcs';
		} else {
			days = 7;
			label = '100+ pcs';
		}

		val.textContent = days + (days === 1 ? ' day' : ' days');
		note.textContent = `Range: ${label} → ${days} day${days>1?'s':''}. Actual queue position assigned by SJF.`;
		box.style.display = 'block';
	}
</script>
<script>
	function addRow() {
		let table = document.getElementById("items");
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

	function removeRow(btn) {
		btn.parentNode.parentNode.remove();
		calcDuration();
	}

	function calcDuration() {
		let qtyInputs = document.querySelectorAll(".qty-input");
		let total = 0;

		qtyInputs.forEach(input => {
			total += parseInt(input.value) || 0;
		});

		let base = parseInt(document.getElementById("product-type").value);
		let duration = (total * base) + 20;

		if (total > 0) {
			document.getElementById("duration-box").style.display = "block";
			document.getElementById("duration-val").innerText = duration + " minutes";
			document.getElementById("est-duration-hidden").value = duration;
		} else {
			document.getElementById("duration-box").style.display = "none";
		}
	}
</script>
</body>

</html>
