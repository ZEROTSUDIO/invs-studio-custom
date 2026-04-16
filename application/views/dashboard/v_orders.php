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
	$ci = &get_instance();
	if (!function_exists('build_sort_url')) {
		function build_sort_url($col)
		{
			$ci = &get_instance();
			$get = $ci->input->get();
			unset($get['per_page']); // reset pagination when sorting
			$order = 'asc';
			if (isset($get['sort_by']) && $get['sort_by'] == $col && isset($get['sort_order']) && $get['sort_order'] == 'asc') {
				$order = 'desc';
			}
			$get['sort_by'] = $col;
			$get['sort_order'] = $order;
			return base_url('dashboard/orders') . '?' . http_build_query($get);
		}
		function sort_icon($col)
		{
			$ci = &get_instance();
			if (isset($ci->input->get()['sort_by']) && $ci->input->get()['sort_by'] == $col) {
				return isset($ci->input->get()['sort_order']) && $ci->input->get()['sort_order'] == 'asc' ? ' &uarr;' : ' &darr;';
			}
			return '';
		}
	}
	?>
	<div class="panel">
		<div class="panel-header" style="flex-wrap:wrap; gap:16px;">
			<div class="panel-title">All Orders <span style="font-size:10px; color: var(--smoke); font-weight:400; margin-left:8px;"><?php echo $total_rows; ?> total</span></div>

			<div style="flex:1;"></div>

			<!-- Filters Form -->
			<form method="GET" action="<?php echo base_url('dashboard/orders'); ?>" style="display:flex; gap:12px; align-items:center; margin:0; flex-wrap:wrap;">
				<?php if (!empty($sort_by)): ?>
					<input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
					<input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
				<?php endif; ?>

				<div style="display:flex; background:rgba(255,255,255,0.05); border-radius:6px; padding:2px;">
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="" onchange="this.form.submit()" style="display:none;" <?php echo empty($filter_status) ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo empty($filter_status) ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">All</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="active" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'active') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'active') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Active</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="pending" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'pending') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'pending') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Pending</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="completed" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'completed') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'completed') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Completed</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="canceled" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'canceled') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'canceled') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Canceled</div>
					</label>
				</div>

				<input type="text" name="q" placeholder="Search orders..." class="form-control" style="width:200px; padding:6px 12px; font-size:12px;" value="<?php echo htmlspecialchars($filter_search); ?>">
				<button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:12px;">Search</button>
				<?php if (!empty($filter_search) || !empty($filter_status)): ?>
					<a href="<?php echo base_url('dashboard/orders'); ?>" style="font-size:12px; color:var(--smoke); text-decoration:underline;">Clear</a>
				<?php endif; ?>
			</form>
		</div>
		<div style="overflow-x:auto;">
			<table class="data-table">
				<thead>
					<tr>
						<th><a href="<?php echo build_sort_url('queue'); ?>" style="color:inherit;text-decoration:none;">Queue #<?php echo sort_icon('queue'); ?></a></th>
						<th><a href="<?php echo build_sort_url('id'); ?>" style="color:inherit;text-decoration:none;">Order ID<?php echo sort_icon('id'); ?></a></th>
						<th>Customer</th>
						<th>Design File</th>
						<th>Qty</th>
						<th>Est. Duration</th>
						<th><a href="<?php echo build_sort_url('deadline'); ?>" style="color:inherit;text-decoration:none;">Deadline<?php echo sort_icon('deadline'); ?></a></th>
						<th>Start Date</th>
						<th>End Date</th>
						<th><a href="<?php echo build_sort_url('status'); ?>" style="color:inherit;text-decoration:none;">Status<?php echo sort_icon('status'); ?></a></th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $o) : ?>
						<?php
						$is_active = in_array($o->status, ['waiting', 'scheduled', 'in_progress']);
						$queue = ($is_active && $o->queue_position) ? sprintf('%02d', $o->queue_position) : '<span style="opacity:0.4;">—</span>';
						$queue_color = ($is_active && $o->queue_position) ? 'var(--ember)' : 'var(--smoke)';
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

								<?php if ($o->status == 'ordered') : ?>
									<form method="POST" action="<?php echo base_url('dashboard/update_status/' . $o->id . '/waiting'); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(232,160,32,0.12); color:var(--ember); border:1px solid rgba(232,160,32,0.25);" onclick="return confirm('Start Schedule this order?');">→ Schedule</button>
									</form>
									<form method="POST" action="<?php echo base_url('dashboard/cancel_order/' . $o->id); ?>" style="margin:0;">
										<button type="submit" class="btn" style="padding:4px 10px; font-size:9px; background:rgba(248,113,113,0.12); color:#f87171; border:1px solid rgba(248,113,113,0.25);" onclick="return confirm('WARNING: Cancel this order? This cannot be undone.');">Cancel</button>
									</form>
								<?php elseif ($o->status == 'waiting') : ?>
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

		<?php if (!empty($pagination)) : ?>
			<div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.05); display:flex; justify-content:flex-end;">
				<style>
					.pagination {
						display: flex;
						list-style: none;
						padding: 0;
						margin: 0;
						gap: 4px;
					}

					.pagination li a,
					.pagination li strong {
						display: block;
						padding: 6px 12px;
						font-size: 13px;
						text-decoration: none;
						color: var(--smoke);
						background: rgba(255, 255, 255, 0.05);
						border-radius: 4px;
						border: 1px solid rgba(255, 255, 255, 0.05);
					}

					.pagination li.active a,
					.pagination li.active strong {
						background: var(--ember);
						color: #000;
						border-color: var(--ember);
						font-weight: bold;
					}

					.pagination li a:hover {
						background: rgba(255, 255, 255, 0.1);
					}
				</style>
				<?php echo $pagination; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
