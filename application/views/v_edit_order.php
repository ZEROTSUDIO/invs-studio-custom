<div class="page active" id="page-edit-order">
	<div style="max-width: 640px;">

		<div style="margin-bottom:22px;">
			<div style="font-family:'Syne',sans-serif; font-weight:800; font-size:18px; color: var(--cream);">
				Edit Order <?php echo htmlspecialchars($order->order_code); ?>
			</div>
			<div style="font-size:11px; color: var(--smoke); margin-top:4px;">
				Editing a scheduled order will regenerate the queue schedule to ensure accuracy.
			</div>
		</div>

		<?php if ($this->input->get('alert') == 'update_failed'): ?>
			<div class="alert" style="background:rgba(248,113,113,0.1); border-color:rgba(248,113,113,0.3); color:#f87171; margin-bottom:16px;">
				⚠ &nbsp;<b>Kesalahan Sistem!</b> Gagal menyimpan data pesanan Anda. Silakan coba lagi.
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo base_url() . 'dashboard/update_order/' . $order->id; ?>" enctype="multipart/form-data">

			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Order Details</div>
				</div>

				<div style="padding:22px;">

					<!-- CUSTOMER DISPLAY -->
					<div class="form-group">
						<label class="form-label">Customer</label>
						<div style="font-weight: 600; color: var(--cream); background: rgba(255,255,255,0.05); padding: 12px; border: 1px solid var(--ghost);">
							<?php echo htmlspecialchars($order->customer_name); ?>
							<br><span style="font-size:11px; color:var(--smoke); font-weight:normal;"><?php echo htmlspecialchars($order->customer_phone); ?></span>
						</div>
					</div>

					<!-- PRODUCT -->
					<div class="form-group">
						<label class="form-label">Product Type</label>
						<select name="product_type" id="product-type" class="form-input" onchange="calcDuration()">
							<option value="10" <?php echo ($order->product_type == 10) ? 'selected' : ''; ?>>T-Shirt (DTF Print)</option>
							<option value="12" <?php echo ($order->product_type == 12) ? 'selected' : ''; ?>>T-Shirt (Screen Print)</option>
							<option value="15" <?php echo ($order->product_type == 15) ? 'selected' : ''; ?>>Hoodie</option>
							<option value="13" <?php echo ($order->product_type == 13) ? 'selected' : ''; ?>>Polo Shirt</option>
							<option value="8" <?php echo ($order->product_type == 8) ? 'selected' : ''; ?>>Tote Bag</option>
						</select>
					</div>

					<!-- DEADLINE -->
					<div class="form-group">
						<label class="form-label">Deadline</label>
						<input name="deadline" id="deadline-input" class="form-input" type="date" required value="<?php echo $order->deadline; ?>">
						<div id="deadline-warning" style="display:none; margin-top:6px; padding:8px 12px; background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.3); font-size:11px; color:#f87171;">
						</div>
						<div id="deadline-hint" style="display:none; margin-top:5px; font-size:11px; color:var(--smoke);">
						</div>
					</div>

					<!-- ORDER ITEMS -->
					<div class="form-group">
						<label class="form-label">Order Items</label>

						<table id="items" style="width:100%; border-collapse: collapse;">
							<tr>
								<th style="text-align:left;">Size</th>
								<th style="text-align:left;">Qty</th>
								<th></th>
							</tr>

							<?php foreach ($items as $item): ?>
							<tr>
								<td>
									<select name="size[]" class="form-input">
										<option <?php echo ($item->size == 'S') ? 'selected' : ''; ?>>S</option>
										<option <?php echo ($item->size == 'M') ? 'selected' : ''; ?>>M</option>
										<option <?php echo ($item->size == 'L') ? 'selected' : ''; ?>>L</option>
										<option <?php echo ($item->size == 'XL') ? 'selected' : ''; ?>>XL</option>
										<option <?php echo ($item->size == 'XXL') ? 'selected' : ''; ?>>XXL</option>
									</select>
								</td>
								<td>
									<input type="number" name="qty[]" class="form-input qty-input" oninput="calcDuration()" required value="<?php echo $item->qty; ?>">
								</td>
								<td>
									<button type="button" onclick="removeRow(this)">X</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>

						<button type="button" onclick="addRow()" style="margin-top:10px;">
							+ Add Size
						</button>
					</div>

					<!-- ESTIMATION -->
					<div id="duration-box" style="padding:12px; background: rgba(232,160,32,0.08); border:1px solid rgba(232,160,32,0.25); margin-bottom:18px;">
						<div style="font-size:10px; color: var(--smoke);">Estimated Production Time</div>
						<div id="duration-val" style="font-size:24px; color: var(--ember);"></div>
					</div>

					<input type="hidden" name="est_duration" id="est-duration-hidden" value="<?php echo $order->est_duration; ?>">

					<!-- NOTES -->
					<div class="form-group">
						<label class="form-label">Special Notes</label>
						<textarea name="notes" class="form-input" rows="3"><?php echo htmlspecialchars($order->notes); ?></textarea>
					</div>

					<!-- FILE -->
					<div class="form-group">
						<label class="form-label">Upload Design File</label>
						<input type="file" name="design_file" class="form-input">
						<?php if ($order->design_file): ?>
							<div style="font-size:11px; color:var(--smoke); margin-top:4px;">
								Current file: <a href="<?php echo base_url('gambar/orders/'.$order->design_file); ?>" target="_blank" style="color:#60a5fa; text-decoration:underline;"><?php echo htmlspecialchars($order->design_file); ?></a>
								<br>Leave blank to keep existing file.
							</div>
						<?php else: ?>
							<div style="font-size:11px; color:var(--smoke); margin-top:4px;">
								No existing design file.
							</div>
						<?php endif; ?>
					</div>

					<!-- BUTTON -->
					<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
						<a href="<?php echo base_url() . 'dashboard/orders' ?>" class="btn btn-ghost">
							Cancel
						</a>
						<button type="submit" class="btn btn-primary" id="submit-btn">
							Update Order →
						</button>
					</div>

				</div>
			</div>

		</form>
	</div>
</div>

<script>
function addRow() {
	const tr = document.createElement('tr');
	tr.innerHTML = `
		<td>
			<select name="size[]" class="form-input">
				<option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option>
			</select>
		</td>
		<td>
			<input type="number" name="qty[]" class="form-input qty-input" oninput="calcDuration()" required>
		</td>
		<td>
			<button type="button" onclick="removeRow(this)">X</button>
		</td>
	`;
	document.getElementById('items').appendChild(tr);
}

function removeRow(btn) {
	const tr = btn.closest('tr');
	if (document.querySelectorAll('.qty-input').length > 1) {
		tr.remove();
		calcDuration();
	}
}

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
	const qts = document.querySelectorAll('.qty-input');
	let total = 0;
	qts.forEach(q => { total += parseInt(q.value) || 0; });

	const base = parseInt(document.getElementById('product-type').value) || 10;
	const setupTime = 30; 
	
	let est = (total > 0) ? (total * base) + setupTime : 0;
	
	if (est > 0) {
		document.getElementById('duration-val').textContent = formatDuration(est);
		document.getElementById('est-duration-hidden').value = est;
		document.getElementById('duration-box').style.display = 'block';
	} else {
		document.getElementById('duration-box').style.display = 'none';
        document.getElementById('est-duration-hidden').value = 0;
	}
}

// Init duration display on load
calcDuration();
</script>
