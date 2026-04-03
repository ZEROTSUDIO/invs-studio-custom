<div class="page" id="page-cust-order">
	<div style="max-width: 640px;">
		<div style="margin-bottom:22px;">
			<div style="font-family:'Syne',sans-serif; font-weight:800; font-size:18px; color: var(--cream);">Submit New Order</div>
			<div style="font-size:11px; color: var(--smoke); margin-top:4px;">Your order will be auto-scheduled using the SJF algorithm.</div>
		</div>

		<div class="panel">
			<div class="panel-header">
				<div class="panel-title">Order Details</div>
			</div>
			<div style="padding:22px;">
				<div class="form-group">
					<label class="form-label">Full Name</label>
					<input class="form-input" type="text" placeholder="e.g. Rafi Maulana" value="Rafi Maulana">
				</div>
				<div class="form-group">
					<label class="form-label">Phone / WhatsApp</label>
					<input class="form-input" type="text" placeholder="08xxxxxxxxxx" value="081234567890">
				</div>
				<div class="form-group">
					<label class="form-label">Product Type</label>
					<select class="form-input">
						<option>T-Shirt (DTF Print)</option>
						<option>T-Shirt (Screen Print)</option>
						<option>Hoodie</option>
						<option>Polo Shirt</option>
						<option>Tote Bag</option>
					</select>
				</div>
				<div class="form-group">
					<label class="form-label">Quantity (pcs)</label>
					<input class="form-input" type="number" placeholder="e.g. 12" id="qty-input" oninput="calcDuration(this.value)">
				</div>

				<!-- Duration estimate box -->
				<div id="duration-box" style="display:none; padding:12px 14px; background: rgba(232,160,32,0.08); border:1px solid rgba(232,160,32,0.25); margin-bottom:18px;">
					<div style="font-size:9px; letter-spacing:0.15em; text-transform:uppercase; color: var(--smoke); margin-bottom:4px;">Estimated Duration (SJF)</div>
					<div id="duration-val" style="font-family:'Bebas Neue'; font-size:28px; color: var(--ember); line-height:1;"></div>
					<div id="duration-note" style="font-size:10px; color: var(--smoke); margin-top:3px;"></div>
				</div>

				<div class="form-group">
					<label class="form-label">Special Notes</label>
					<textarea class="form-input" rows="3" placeholder="Color preferences, size breakdown, etc."></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Upload Design File</label>
					<div class="file-drop" onclick="document.getElementById('file-upload').click()">
						<div style="font-size:22px; margin-bottom:8px; color: var(--smoke);">↑</div>
						<div style="font-size:12px; color: var(--smoke);">Click to upload or drag & drop</div>
						<div style="font-size:10px; color: var(--ghost); margin-top:4px;">PNG, PDF, AI — max 20MB</div>
						<input type="file" id="file-upload" style="display:none;">
					</div>
				</div>

				<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
					<button class="btn btn-ghost" onclick="showPage('cust-dashboard', null)">Cancel</button>
					<button class="btn btn-primary">Submit Order →</button>
				</div>
			</div>
		</div>
	</div>
</div>
