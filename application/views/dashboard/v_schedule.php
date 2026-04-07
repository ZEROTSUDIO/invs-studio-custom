<div class="page active">
	<!-- Filter bar -->
	<div style="display:flex; justify-content:space-between; margin-bottom:18px; align-items:center;">
		<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; color: var(--cream);">Production Scheduling</div>
		<form method="POST" action="<?php echo base_url('dashboard/generate_schedule'); ?>">
			<button type="submit" class="btn btn-primary" onclick="return confirm('Recalculate entire schedule using SJF algorithm?');">
				⟳ Generate Schedule
			</button>
		</form>
	</div>

	<?php if (isset($_GET['alert'])) : ?>
		<?php if ($_GET['alert'] == 'schedule_updated') : ?>
			<div class="alert">
				⟳ &nbsp;SJF algorithm recalculated — queue reordered successfully. All start/end dates updated automatically.
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div style="display:grid; grid-template-columns: 1fr 300px; gap:16px;">

		<!-- Gantt Chart -->
		<div class="panel">
			<div class="panel-header">
				<div class="panel-title">Production Timeline</div>
				<div style="display:flex; gap:14px; font-size:10px; color: var(--smoke);">
					<span><span style="color:#4ade80;">●</span> In Production</span>
					<span><span style="color: var(--ember);">●</span> Queued</span>
					<span><span style="color: var(--smoke);">●</span> Done</span>
				</div>
			</div>
			<div style="padding: 18px 20px;">

				<!-- Date headers -->
				<div style="display:grid; grid-template-columns: 160px repeat(10, 1fr); gap:0; margin-bottom:12px;">
					<div></div>
					<?php
					$today = date('Y-m-d');
					for ($i = 0; $i < 10; $i++) {
						$date_label = date('j M', strtotime("$today +$i days"));
						echo '<div style="font-size:9px; color: var(--smoke); letter-spacing:0.1em; text-align:center; border-left:1px solid var(--ghost); padding:3px;">' . $date_label . '</div>';
					}
					
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
				</div>

				<!-- Gantt Rows -->
				<div style="display:flex; flex-direction:column; gap:6px;">
					<?php foreach ($schedules as $s) : ?>
						<?php
						// Calculate grid column positions base 1
						// Col 1 = Label
						// Col 2 = Today
						// Col 3 = Tomorrow, etc.
						$start_dt = new DateTime(date('Y-m-d', strtotime($s->start_date)));
						$end_dt = new DateTime(date('Y-m-d', strtotime($s->end_date)));
						$base_dt = new DateTime($today);

						$diff_start = $base_dt->diff($start_dt)->days;
						$is_start_past = (clone $start_dt)->setTime(0, 0) < (clone $base_dt)->setTime(0, 0);
						if ($is_start_past) {
							$diff_start = 0; // cap at today visually
						}

						$duration = $start_dt->diff($end_dt)->days;
						// if duration is 0 (same day), minimum 1 day block
						if ($duration == 0) $duration = 1;

						$grid_start = 2 + $diff_start;
						$grid_end = $grid_start + $duration;

						// Cap to 10 days max (col 12)
						if ($grid_end > 12) $grid_end = 12;

						// Determine color based on status
						if ($s->status == 'in_progress') {
							$bg = '#1a5c2a';
							$col = '#4ade80';
						} elseif ($s->status == 'scheduled') {
							$bg = '#7a4010';
							$col = 'var(--ember)';
						} else {
							$bg = 'var(--ghost)';
							$col = 'var(--cream)';
						}
						
						// Skip if task starts way past 10 days
						if ($grid_start > 11) continue;
						?>

						<div style="display:grid; grid-template-columns: 160px repeat(10, 1fr); gap:0; align-items:center;">
							<div style="font-size:11px; color: var(--cream); padding-right:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
								<?php echo htmlspecialchars($s->order_code); ?> <?php echo htmlspecialchars($s->customer_name); ?>
							</div>

							<?php if ($grid_start > 2) : ?>
								<div style="grid-column: 2 / <?php echo $grid_start; ?>; background: rgba(255,255,255,0.02); height:28px;"></div>
							<?php endif; ?>

							<div style="grid-column: <?php echo $grid_start; ?> / <?php echo $grid_end; ?>;">
								<div class="gantt-bar" style="background:<?php echo $bg; ?>; color:<?php echo $col; ?>;">
									<?php echo $s->qty; ?> pcs · <?php echo format_duration($s->est_duration); ?>
								</div>
							</div>

							<?php if ($grid_end <= 11) : ?>
								<div style="grid-column: <?php echo $grid_end; ?> / 12; background: rgba(255,255,255,0.02); height:28px;"></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
					
					<?php if (count($schedules) == 0): ?>
					    <div style="font-size:11px; color:var(--smoke); margin-top:10px;">No scheduled productions. Generate schedule to populate.</div>
					<?php endif; ?>
				</div>

				<!-- Today marker line (visual only) -->
				<div style="margin-top:8px; font-size:9px; color: var(--ember); letter-spacing:0.12em; padding-left:160px;">
					▲ TODAY (Col 1 = <?php echo date('M j'); ?>)
				</div>
			</div>
		</div>

		<!-- SJF Details -->
		<div style="display:flex; flex-direction:column; gap:14px;">
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Algorithm Info</div>
				</div>
				<div style="padding:16px;">
					<div style="font-size:11px; color: var(--smoke); line-height:1.7; margin-bottom:14px;">
						Shortest Job First (SJF) sorts pending orders by <span style="color: var(--ember);">deadline</span> (if urgent) and <span style="color: var(--ember);">est_duration</span> ascending. Process recalculates start dates automatically.
					</div>
					<div style="display:flex; flex-direction:column; gap:8px;">
						<div style="display:flex; justify-content:space-between; font-size:11px; padding:6px 10px; background: var(--ash);">
							<span style="color: var(--smoke);">Urgent threshold</span>
							<span style="color: var(--ember);">rem_days <= est*1.5</span>
						</div>
					</div>
				</div>
			</div>

			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Queue Stats</div>
				</div>
				<div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
					<?php
					$shortest = null;
					$longest = null;
					$total_dur = 0;
					$count = count($schedules);

					foreach ($schedules as $s) {
						if ($shortest === null || $s->est_duration < $shortest) $shortest = $s->est_duration;
						if ($longest === null || $s->est_duration > $longest) $longest = $s->est_duration;
						$total_dur += $s->est_duration;
					}

					$avg = $count > 0 ? round($total_dur / $count, 1) : 0;
					$load = $count > 10 ? 'High' : ($count > 5 ? 'Moderate' : 'Low');
					$load_color = $count > 10 ? '#f87171' : ($count > 5 ? '#e8a020' : '#4ade80');
					?>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Active Queue</span>
						<span style="font-size:11px; color: var(--ember); font-weight:600;"><?php echo $count; ?> jobs</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Shortest wait</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($shortest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Longest wait</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($longest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Avg wait time</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($avg); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Capacity load</span>
						<span style="font-size:11px; color: <?php echo $load_color; ?>;"><?php echo $load; ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
