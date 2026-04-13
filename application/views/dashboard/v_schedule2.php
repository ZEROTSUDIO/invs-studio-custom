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
						$ts = strtotime("$today +$i days");
						$date_label = date('j M', $ts);
						$is_sunday = date('w', $ts) == 0;
						$bg_style = $is_sunday ? 'background: rgba(239, 68, 68, 0.05); color: var(--ember);' : 'color: var(--smoke);';
						$label_append = $is_sunday ? '<br><span style="font-size:7px; opacity:0.7">OFF</span>' : '';
						echo '<div style="font-size:9px; letter-spacing:0.1em; text-align:center; border-left:1px solid var(--ghost); padding:3px; ' . $bg_style . '">' . $date_label . $label_append . '</div>';
					}
					?>
				</div>

				<!-- Gantt Rows -->
				<div style="display:flex; flex-direction:column; gap:6px;">
					<?php
					$grid_start_date = date('Y-m-d');
					
					// Helper to calculate hybrid grid percentage
					if (!function_exists('get_hybrid_grid_pct')) {
						function get_hybrid_grid_pct($target_date_str, $grid_start_str) {
							$target = new DateTime($target_date_str);
							$target_day = new DateTime($target->format('Y-m-d') . ' 00:00:00');
							$start_day = new DateTime($grid_start_str . ' 00:00:00');
							
							// Prevent pushing items off the left side of the screen
							if ($target_day < $start_day) return 0;
							
							$diff = $start_day->diff($target_day);
							$day_diff = $diff->invert ? -$diff->days : $diff->days;
							
							// Mins elapsed in target's day
							$mins = ((int)$target->format('G') * 60) + (int)$target->format('i');
							
							// Clamp to working hours (08:30 to 17:00) so "night time" doesn't render
							if ($mins < 510) $mins = 510;
							if ($mins > 1020) $mins = 1020;
							
							// Scale 0-1 across the column based on working hours
							$col_fraction = ($mins - 510) / 510;
							
							// 10 columns = 10% per day
							return ($day_diff * 10) + ($col_fraction * 10);
						}
					}
					?>
					<?php foreach ($schedules as $s) : ?>
						<?php
						$actual_start = $s->start_date;
						
						// Clamp old tasks so they start evenly at today's open time
						if (new DateTime($actual_start) < new DateTime($grid_start_date . ' 08:30:00')) {
						    $actual_start = $grid_start_date . ' 08:30:00';
						}
						
						// Calculate Left and End positions, then width
						$left_pct = get_hybrid_grid_pct($actual_start, $grid_start_date);
						$end_pct = get_hybrid_grid_pct($s->end_date, $grid_start_date);
						
						$width_pct = $end_pct - $left_pct;
						if ($width_pct < 0) $width_pct = 0;
						
						// Skip if task starts completely outside the 10-day block
						if ($left_pct >= 100) continue;
						
						// Clamp width if it bleeds over
						if (($left_pct + $width_pct) > 100) {
						    $width_pct = 100 - $left_pct;
						}

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
						?>

						<div style="display:flex; align-items:center; height:28px;">
							<div style="font-size:11px; color:var(--cream); width:160px; min-width:160px; padding-right:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
								<?php echo htmlspecialchars($s->order_code); ?> <?php echo htmlspecialchars($s->customer_name); ?>
							</div>

							<div style="flex:1; position:relative; height:100%; background:rgba(255,255,255,0.02); display:grid; grid-template-columns: repeat(10, 1fr);">
							    <?php for ($i=0; $i<10; $i++): ?>
							        <?php 
                                        $ts = strtotime("$today +$i days");
                                        $is_sunday = date('w', $ts) == 0;
                                        $col_bg = $is_sunday ? 'background: rgba(239, 68, 68, 0.05);' : '';
                                    ?>
							        <div style="border-left:1px solid var(--ghost); <?php echo $col_bg; ?>"></div>
							    <?php endfor; ?>
								<div class="gantt-bar flex justify-center items-center" style="position:absolute; top:0; bottom:0; left:<?php echo $left_pct; ?>%; width:<?php echo $width_pct; ?>%; height:100%; background:<?php echo $bg; ?>; color:<?php echo $col; ?>; min-width:20px; overflow:hidden; white-space:nowrap; text-align:center;">
									<?php echo $s->qty; ?>p · <?php echo format_duration($s->est_duration); ?>
								</div>
							</div>
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
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Active Queue</span>
						<span style="font-size:11px; color: var(--ember); font-weight:600;"><?php echo $stats->count; ?> jobs</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Shortest wait</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($stats->shortest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Longest wait</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($stats->longest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Avg wait time</span>
						<span style="font-size:11px; color: var(--cream);"><?php echo format_duration($stats->avg); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color: var(--smoke);">Capacity load</span>
						<span style="font-size:11px; color: <?php echo $stats->load_color; ?>;"><?php echo $stats->load; ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
