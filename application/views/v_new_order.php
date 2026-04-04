<div class="page active" id="page-cust-order">
	<div style="max-width: 640px;">

		<div style="margin-bottom:22px;">
			<div style="font-family:'Syne',sans-serif; font-weight:800; font-size:18px; color: var(--cream);">
				Input New Order
			</div>
			<div style="font-size:11px; color: var(--smoke); margin-top:4px;">
				Order will be added to queue and scheduled by admin.
			</div>
		</div>

		<form method="post" action="/orders/store" enctype="multipart/form-data">

			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Order Details</div>
				</div>

				<div style="padding:22px;">

					<!-- CUSTOMER -->
					<div class="form-group">
						<label class="form-label">Full Name</label>
						<input name="customer_name" class="form-input" type="text" required>
					</div>

					<div class="form-group">
						<label class="form-label">Phone / WhatsApp</label>
						<input name="phone" class="form-input" type="text" required>
					</div>

					<!-- PRODUCT -->
					<div class="form-group">
						<label class="form-label">Product Type</label>
						<select name="product_type" id="product-type" class="form-input" onchange="calcDuration()">
							<option value="10">T-Shirt (DTF Print)</option>
							<option value="12">T-Shirt (Screen Print)</option>
							<option value="15">Hoodie</option>
							<option value="13">Polo Shirt</option>
							<option value="8">Tote Bag</option>
						</select>
					</div>

					<!-- DEADLINE -->
					<div class="form-group">
						<label class="form-label">Deadline</label>
						<input name="deadline" class="form-input" type="date" required>
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

							<tr>
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
							</tr>
						</table>

						<button type="button" onclick="addRow()" style="margin-top:10px;">
							+ Add Size
						</button>
					</div>

					<!-- ESTIMATION -->
					<div id="duration-box" style="display:none; padding:12px; background: rgba(232,160,32,0.08); border:1px solid rgba(232,160,32,0.25); margin-bottom:18px;">
						<div style="font-size:10px; color: var(--smoke);">Estimated Production Time</div>
						<div id="duration-val" style="font-size:24px; color: var(--ember);"></div>
					</div>

					<input type="hidden" name="est_duration" id="est-duration-hidden">

					<!-- NOTES -->
					<div class="form-group">
						<label class="form-label">Special Notes</label>
						<textarea name="notes" class="form-input" rows="3"></textarea>
					</div>

					<!-- FILE -->
					<div class="form-group">
						<label class="form-label">Upload Design File</label>
						<input type="file" name="design_file" class="form-input">
					</div>

					<!-- BUTTON -->
					<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
						<a href="<?php echo base_url() . 'dashboard' ?>" class="btn btn-ghost">
							Cancel
						</a>
						<button type="submit" class="btn btn-primary">
							Submit Order →
						</button>
					</div>

				</div>
			</div>

		</form>
	</div>
</div>
