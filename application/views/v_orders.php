<div class="page active" id="page-admin-orders">
	<!-- Filter bar -->
	<div style="display:flex; gap:10px; margin-bottom:18px; align-items:center;">
		<input class="form-input" placeholder="Search order ID / customer name..." style="max-width:300px;">
		<select class="form-input" style="width:160px;">
			<option>All Statuses</option>
			<option>Waiting</option>
			<option>Scheduled</option>
			<option>In Progress</option>
			<option>Done</option>
		</select>
		<div style="margin-left:auto; display:flex; gap:8px;">
			<button class="btn btn-ghost">↓ Export CSV</button>
			<a href="<?php echo base_url('dashboard/new_order') ?>" class="btn btn-primary" style="text-decoration:none;">+ New Order</a>
		</div>
	</div>

	<div class="panel">
		<div class="panel-header">
			<div class="panel-title">All Orders <span style="font-size:10px; color: var(--smoke); font-weight:400; margin-left:8px;"><?php echo count($orders); ?> total</span></div>
		</div>
		<div style="overflow-x:auto;">
			<table class="data-table">
				<thead>
					<tr>
						<th>No</th>
						<th>Order ID</th>
						<th>Customer</th>
						<th>Design File</th>
						<th>Qty</th>
						<th>Est. Min</th>
						<th>Deadline</th>
						<th>Created At</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($orders as $o) {
						// badge formatting
						$badgeClass = 'badge-pending';
						if ($o->status == 'waiting') $badgeClass = 'badge-pending';
						else if ($o->status == 'scheduled') $badgeClass = 'badge-confirmed';
						else if ($o->status == 'in_progress') $badgeClass = 'badge-production';
						else if ($o->status == 'done') $badgeClass = 'badge-done';

					?>
						<tr>
							<td><span style="font-family:'Bebas Neue'; font-size:18px; color: var(--smoke);"><?php echo str_pad($no++, 2, '0', STR_PAD_LEFT); ?></span></td>
							<td style="color: var(--ember);"><?php echo $o->order_code; ?></td>
							<td><?php echo $o->customer_name; ?></td>
							<td>
								<?php if ($o->design_file) { ?>
									<a href="<?php echo base_url('gambar/orders/' . $o->design_file) ?>" target="_blank" style="color:#60a5fa; text-decoration:underline;"><?php echo substr($o->design_file, 0, 15) . (strlen($o->design_file)>15?'...':''); ?></a>
								<?php } else { ?>
									<span style="color: var(--smoke);">No file</span>
								<?php } ?>
							</td>
							<td><?php echo $o->qty; ?> pcs</td>
							<td><?php echo $o->est_duration; ?> mnt</td>
							<td><?php echo $o->deadline ? date('d M Y', strtotime($o->deadline)) : '-'; ?></td>
							<td><?php echo date('d M', strtotime($o->created_at)); ?></td>
							<td><span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $o->status)); ?></span></td>
							<td style="display:flex; gap:6px; padding: 8px 16px;">
								<button class="btn btn-ghost" style="padding:4px 10px; font-size:9px;">View</button>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
