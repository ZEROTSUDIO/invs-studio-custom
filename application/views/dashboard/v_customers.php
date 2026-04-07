<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="page active">
	<div style="display:flex; justify-content:space-between; margin-bottom:18px; align-items:center;">
		<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; color: var(--cream);">Customer Directory</div>
	</div>

	<div class="panel">
		<div class="panel-header">
			<div class="panel-title">
				All Customers
				<span style="font-size:10px; color: var(--smoke); font-weight:400; margin-left:8px;"><?php echo count($customers); ?> registered</span>
			</div>
		</div>
		<div style="overflow-x:auto;">
			<table class="data-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Phone</th>
						<th style="text-align:center;">Total Orders</th>
					</tr>
				</thead>
				<tbody>
					<?php if (count($customers) == 0): ?>
						<tr>
							<td colspan="4" style="text-align:center; padding:24px; color:var(--smoke);">No customers yet.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($customers as $i => $c): ?>
							<tr>
								<td style="color:var(--smoke); width:36px;"><?php echo $i + 1; ?></td>
								<td style="font-weight:600; color:var(--cream);"><?php echo htmlspecialchars($c->name); ?></td>
								<td style="color:var(--smoke); font-family:'JetBrains Mono', monospace; font-size:12px;"><?php echo htmlspecialchars($c->phone); ?></td>
								<td style="text-align:center;">
									<span style="display:inline-block; padding:2px 10px; background:rgba(232,160,32,0.1); color:var(--ember); font-size:12px; font-weight:600;">
										<?php echo $c->total_orders; ?>
									</span>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
