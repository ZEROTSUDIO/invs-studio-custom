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

</body>

</html>
