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

		<?php if ($this->input->get('alert') == 'no_customer'): ?>
			<div class="alert" style="background:rgba(248,113,113,0.1); border-color:rgba(248,113,113,0.3); color:#f87171; margin-bottom:16px;">
				⚠ &nbsp;Please select or create a customer before submitting.
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo base_url() . 'dashboard/save_order' ?>" enctype="multipart/form-data">

			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Order Details</div>
				</div>

				<div style="padding:22px;">

					<!-- CUSTOMER PICKER -->
					<div class="form-group" id="customer-picker-group">
						<label class="form-label">Customer</label>

						<!-- Hidden field submitted with form -->
						<input type="hidden" name="customer_id" id="customer-id-val">

						<!-- Search input -->
						<div style="position:relative;">
							<input
								type="text"
								id="customer-search-input"
								class="form-input"
								placeholder="Type name or phone to search…"
								autocomplete="off"
							>
							<!-- Dropdown results -->
							<div id="customer-dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:200; background:#1a1a1a; border:1px solid var(--ghost); border-top:none; max-height:220px; overflow-y:auto;">
							</div>
						</div>

						<!-- Selected customer chip -->
						<div id="customer-chip" style="display:none; margin-top:10px; padding:10px 14px; background:rgba(232,160,32,0.08); border:1px solid rgba(232,160,32,0.3); display:none; align-items:center; justify-content:space-between;">
							<div>
								<div id="chip-name" style="font-size:13px; color:var(--cream); font-weight:600;"></div>
								<div id="chip-phone" style="font-size:11px; color:var(--smoke);"></div>
							</div>
							<button type="button" onclick="clearCustomer()" style="background:none; border:none; color:var(--smoke); cursor:pointer; font-size:16px; padding:0 4px;">✕</button>
						</div>

						<!-- New customer inline form -->
						<div id="new-customer-form" style="display:none; margin-top:12px; padding:14px; border:1px solid var(--ghost); background:rgba(255,255,255,0.02);">
							<div style="font-size:11px; color:var(--smoke); margin-bottom:10px; letter-spacing:0.1em; text-transform:uppercase;">New Customer</div>
							<div class="form-group" style="margin-bottom:10px;">
								<input type="text" id="new-name" class="form-input" placeholder="Full name" style="margin-bottom:0;">
							</div>
							<div class="form-group" style="margin-bottom:10px;">
								<input type="text" id="new-phone" class="form-input" placeholder="Phone / WhatsApp" style="margin-bottom:0;">
							</div>
							<div style="display:flex; gap:8px;">
								<button type="button" onclick="submitNewCustomer()" class="btn btn-primary" style="font-size:11px; padding:6px 14px;">Create & Select</button>
								<button type="button" onclick="toggleNewCustomerForm(false)" class="btn btn-ghost" style="font-size:11px; padding:6px 14px;">Cancel</button>
							</div>
							<div id="new-customer-error" style="color:#f87171; font-size:11px; margin-top:8px; display:none;"></div>
						</div>

						<!-- Show new customer button -->
						<div id="new-customer-trigger" style="margin-top:8px;">
							<button type="button" onclick="toggleNewCustomerForm(true)" style="background:none; border:none; color:var(--smoke); font-size:11px; cursor:pointer; padding:0; text-decoration:underline;">
								+ New customer
							</button>
						</div>
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
						<button type="submit" class="btn btn-primary" id="submit-btn" disabled style="opacity:0.4; cursor:not-allowed;">
							Submit Order →
						</button>
					</div>

				</div>
			</div>

		</form>
	</div>
</div>

<style>
#customer-dropdown .customer-option {
	padding: 10px 14px;
	cursor: pointer;
	border-bottom: 1px solid var(--ghost);
	transition: background 0.15s;
}
#customer-dropdown .customer-option:hover {
	background: rgba(232,160,32,0.1);
}
#customer-dropdown .customer-option .opt-name {
	font-size: 13px;
	color: var(--cream);
	font-weight: 600;
}
#customer-dropdown .customer-option .opt-phone {
	font-size: 11px;
	color: var(--smoke);
}
</style>

<script>
const SEARCH_URL = '<?php echo base_url("dashboard/customer_search"); ?>';
const CREATE_URL = '<?php echo base_url("dashboard/customer_create"); ?>';

let searchTimeout = null;

document.getElementById('customer-search-input').addEventListener('input', function() {
	const q = this.value.trim();
	clearTimeout(searchTimeout);
	if (q.length < 1) { hideDropdown(); return; }
	searchTimeout = setTimeout(() => liveSearch(q), 250);
});

document.getElementById('customer-search-input').addEventListener('blur', function() {
	// Small delay so click on dropdown registers first
	setTimeout(hideDropdown, 200);
});

function liveSearch(q) {
	fetch(SEARCH_URL + '?q=' + encodeURIComponent(q))
		.then(r => r.json())
		.then(data => {
			const dd = document.getElementById('customer-dropdown');
			dd.innerHTML = '';
			if (data.length === 0) {
				dd.innerHTML = '<div style="padding:10px 14px; font-size:11px; color:var(--smoke);">No matches — use "+ New customer" below.</div>';
			} else {
				data.forEach(c => {
					const el = document.createElement('div');
					el.className = 'customer-option';
					el.innerHTML = `<div class="opt-name">${c.name}</div><div class="opt-phone">${c.phone}</div>`;
					el.addEventListener('mousedown', () => selectCustomer(c.id, c.name, c.phone));
					dd.appendChild(el);
				});
			}
			dd.style.display = 'block';
		});
}

function hideDropdown() {
	document.getElementById('customer-dropdown').style.display = 'none';
}

function selectCustomer(id, name, phone) {
	document.getElementById('customer-id-val').value = id;
	document.getElementById('customer-search-input').style.display = 'none';
	document.getElementById('new-customer-trigger').style.display = 'none';

	const chip = document.getElementById('customer-chip');
	document.getElementById('chip-name').textContent = name;
	document.getElementById('chip-phone').textContent = phone;
	chip.style.display = 'flex';
	hideDropdown();

	// Enable submit
	const btn = document.getElementById('submit-btn');
	btn.disabled = false;
	btn.style.opacity = '1';
	btn.style.cursor = 'pointer';
}

function clearCustomer() {
	document.getElementById('customer-id-val').value = '';
	document.getElementById('customer-search-input').value = '';
	document.getElementById('customer-search-input').style.display = '';
	document.getElementById('new-customer-trigger').style.display = '';
	document.getElementById('customer-chip').style.display = 'none';

	// Disable submit again
	const btn = document.getElementById('submit-btn');
	btn.disabled = true;
	btn.style.opacity = '0.4';
	btn.style.cursor = 'not-allowed';
}

function toggleNewCustomerForm(show) {
	document.getElementById('new-customer-form').style.display = show ? 'block' : 'none';
	document.getElementById('new-customer-trigger').style.display = show ? 'none' : '';
	hideDropdown();
}

function submitNewCustomer() {
	const name  = document.getElementById('new-name').value.trim();
	const phone = document.getElementById('new-phone').value.trim();
	const errEl = document.getElementById('new-customer-error');
	errEl.style.display = 'none';

	if (!name || !phone) {
		errEl.textContent = 'Name and phone are required.';
		errEl.style.display = 'block';
		return;
	}

	const fd = new FormData();
	fd.append('name', name);
	fd.append('phone', phone);

	fetch(CREATE_URL, { method: 'POST', body: fd })
		.then(r => r.json())
		.then(data => {
			if (data.success) {
				toggleNewCustomerForm(false);
				selectCustomer(data.id, data.name, data.phone);
			} else {
				errEl.textContent = data.message || 'Failed to create customer.';
				errEl.style.display = 'block';
			}
		});
}
</script>
