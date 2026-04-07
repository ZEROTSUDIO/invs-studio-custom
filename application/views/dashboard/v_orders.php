<div class="page active">
	<!-- Filter bar -->
	<div style="display:flex; justify-content:space-between; margin-bottom:18px; align-items:center;">
		<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; color: var(--cream);">Orders Management</div>
		<div style="display:flex; gap:8px;">
			<a href="<?php echo base_url('dashboard/new_order'); ?>" class="btn btn-primary">+ New Order</a>
		</div>
	</div>

	<?php if (isset($_GET['alert'])) : ?>
		<?php if ($_GET['alert'] == 'status_updated') : ?>
			<div class="alert">
				✓ &nbsp;Order status has been updated successfully.
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	// Helper function for minutes format
	if (!function_exists('format_duration')) {
	    function format_duration($mins) {
	        if ($mins <= 0) return "0 mins";
	        $d = floor($mins / 510);
	        $rem = $mins % 510;
	        $h = floor($rem / 60);
	        $m = $rem % 60;
	        $parts = [];
	        if ($d > 0) $parts[] = $d . "d";
	        if ($h > 0) $parts[] = $h . "h";
	        if ($m > 0) $parts[] = $m . "m";
	        return implode(" ", $parts);
	    }
	}
	?>

	<div class="panel">
		<div class="panel-header">
			<div class="panel-title">All Orders <span style="font-size:10px; color: var(--smoke); font-weight:400; margin-left:8px;"><?php echo count($orders); ?> total</span></div>
		</div>
		<div style="overflow-x:auto;">
			<table class="data-table">
				<thead>
					<tr>
						<th>Queue #</th>
						<th>Order ID</th>
						<th>Customer</th>
						<th>Design File</th>
						<th>Qty</th>
						<th>Est. Duration</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $o) : ?>
						<?php
						// Badges logic
						if ($o->status == 'waiting') {
							$badge_class = 'badge-pending';
							$badge_text = 'Waiting';
						} elseif ($o->status == 'scheduled') {
							$badge_class = 'badge-confirmed';
							$badge_text = 'Scheduled';
						} elseif ($o->status == 'in_progress') {
							$badge_class = 'badge-production';
							$badge_text = 'In Production';
						} elseif ($o->status == 'done') {
							$badge_class = 'badge-done';
							$badge_text = 'Done';
						} else {
							$badge_class = 'badge-pending';
							$badge_text = ucfirst($o->status);
						}

						// Dates formatting
						$start = $o->start_date ? date('j M', strtotime($o->start_date)) : '<span style="color:var(--smoke)">TBD</span>';
						$end = $o->end_date ? date('j M', strtotime($o->end_date)) : '<span style="color:var(--smoke)">TBD</span>';
						$queue = $o->queue_position ? sprintf('%02d', $o->queue_position) : '<span style="opacity:0.4;">—</span>';
						$queue_color = $o->queue_position ? 'var(--ember)' : 'var(--smoke)';

						// Design file formatting
						if ($o->design_file) {
							$file_url = base_url() . 'gambar/orders/' . $o->design_file;
							$file_link = '<a href="' . $file_url . '" target="_blank" style="color:#60a5fa; text-decoration:underline; cursor:pointer;" download>' . htmlspecialchars($o->design_file) . '</a>';
						} else {
							$file_link = '<span style="color: var(--smoke);">No file</span>';
						}
						?>
						<tr>
							<td><span style="font-family:'Bebas Neue'; font-size:18px; color: <?php echo $queue_color; ?>;"><?php echo $queue; ?></span></td>
							<td style="color: var(--ember);"><?php echo htmlspecialchars($o->order_code); ?></td>
							<td><?php echo htmlspecialchars($o->customer_name); ?></td>
							<td><?php echo $file_link; ?></td>
							<td><?php echo $o->qty; ?> pcs</td>
							<td><?php echo format_duration($o->est_duration); ?></td>
							<td><?php echo $start; ?></td>
							<td><?php echo $end; ?></td>
							<td><span class="badge <?php echo $badge_class; ?>"><?php echo $badge_text; ?></span></td>
							<td style="display:flex; gap:6px; padding: 8px 16px;">
								<?php if ($o->status == 'waiting') : ?>
									<span style="font-size:9px; color:var(--smoke); margin-top:5px;">Waiting for schedule</span>
								<?php elseif ($o->status == 'scheduled') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/update_status/' . $o->id . '/in_progress'); ?>">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(232,160,32,0.12); color:var(--ember); border:1px solid rgba(232,160,32,0.25);" onclick="return confirm('Start producing this order?');">→ Produce</button>
									</form>
								<?php elseif ($o->status == 'in_progress') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/update_status/' . $o->id . '/done'); ?>">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(34,197,94,0.12); color:#4ade80; border:1px solid rgba(34,197,94,0.25);" onclick="return confirm('Mark this order as done?');">→ Done</button>
									</form>
								<?php elseif ($o->status == 'done') : ?>
									<!-- Empty action or maybe Picked Up later -->
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					
					<?php if (count($orders) == 0): ?>
						<tr>
							<td colspan="10" style="text-align:center; padding: 24px; color: var(--smoke);">No orders found.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
