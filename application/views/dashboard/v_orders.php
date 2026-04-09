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
						<th>Deadline</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $o) : ?>
						<?php
						$queue = $o->queue_position ? sprintf('%02d', $o->queue_position) : '<span style="opacity:0.4;">—</span>';
						$queue_color = $o->queue_position ? 'var(--ember)' : 'var(--smoke)';
						?>
						<tr>
							<td><span style="font-family:'Bebas Neue'; font-size:18px; color: <?php echo $queue_color; ?>;"><?php echo $queue; ?></span></td>
							<td style="color: var(--ember);"><?php echo htmlspecialchars($o->order_code); ?></td>
							<td><?php echo htmlspecialchars($o->customer_name); ?></td>
							<td><?php echo render_design_link($o->design_file); ?></td>
							<td><?php echo $o->qty; ?> pcs</td>
							<td><?php echo format_duration($o->est_duration); ?></td>
							<td><?php echo format_order_date($o->deadline); ?></td>
							<td><?php echo format_order_date($o->start_date); ?></td>
							<td><?php echo format_order_date($o->end_date); ?></td>
							<td><?php echo render_status_badge($o->status); ?></td>
							<td style="display:flex; gap:6px; padding: 8px 16px;">
								<?php if ($o->status != 'done' && $o->status != 'canceled') : ?>
									<a href="<?php echo base_url('dashboard/edit_order/' . $o->id); ?>" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(255,255,255,0.05); color:var(--cream); border:1px solid rgba(255,255,255,0.2);">Edit</a>
								<?php endif; ?>

								<?php if ($o->status == 'waiting') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/cancel_order/' . $o->id); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(248,113,113,0.12); color:#f87171; border:1px solid rgba(248,113,113,0.25);" onclick="return confirm('WARNING: Cancel this order? This cannot be undone.');">Cancel</button>
									</form>
								<?php elseif ($o->status == 'scheduled') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/update_status/' . $o->id . '/in_progress'); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(232,160,32,0.12); color:var(--ember); border:1px solid rgba(232,160,32,0.25);" onclick="return confirm('Start producing this order?');">→ Produce</button>
									</form>
									<form method="POST" action="<?php echo base_url('dashboard/cancel_order/' . $o->id); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(248,113,113,0.12); color:#f87171; border:1px solid rgba(248,113,113,0.25);" onclick="return confirm('WARNING: Cancel this order? This cannot be undone.');">Cancel</button>
									</form>
								<?php elseif ($o->status == 'in_progress') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/update_status/' . $o->id . '/done'); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(34,197,94,0.12); color:#4ade80; border:1px solid rgba(34,197,94,0.25);" onclick="return confirm('Mark this order as done?');">→ Done</button>
									</form>
									<form method="POST" action="<?php echo base_url('dashboard/cancel_order/' . $o->id); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(248,113,113,0.12); color:#f87171; border:1px solid rgba(248,113,113,0.25);" onclick="return confirm('WARNING: Cancel this order? This cannot be undone.');">Cancel</button>
									</form>
								<?php elseif ($o->status == 'canceled') : ?>
									<span style="font-size:9px; color:#f87171; margin-top:5px; font-weight:600;">CANCELED</span>
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
