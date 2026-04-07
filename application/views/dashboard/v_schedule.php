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
					    
					    function get_operational_minutes($start_txt, $end_txt) {
					        $start = new DateTime($start_txt);
					        $end = new DateTime($end_txt);
					        if ($start >= $end) return 0;
					        
					        $minutes = 0;
					        while ($start < $end) {
					            if ($start->format('w') == 0) { // skip sunday
					                $start->modify('+1 day')->setTime(8,30,0);
					                continue;
					            }
					            $curr_mins = (int)$start->format('G') * 60 + (int)$start->format('i');
					            
					            // cap start bounds
					            if ($curr_mins < 510) { $start->setTime(8,30,0); $curr_mins = 510; }
					            if ($curr_mins >= 1020) { $start->modify('+1 day')->setTime(8,30,0); continue; }
					            
					            if ($start->format('Y-m-d') == $end->format('Y-m-d')) {
					                $eh = (int)$end->format('G');
					                $em = (int)$end->format('i');
					                $end_mins = $eh * 60 + $em;
					                if ($end_mins > 1020) $end_mins = 1020;
					                $minutes += max(0, $end_mins - $curr_mins);
					                break;
					            } else {
					                $minutes += max(0, 1020 - $curr_mins);
					                $start->modify('+1 day')->setTime(8,30,0);
					            }
					        }
					        return $minutes;
					    }
					}
					?>
				</div>

				<!-- Gantt Rows -->
				<div style="display:flex; flex-direction:column; gap:6px;">
					<?php foreach ($schedules as $s) : ?>
						<?php
						$today_start = date('Y-m-d') . ' 08:30:00';
						
						$actual_start = $s->start_date;
						if (new DateTime($actual_start) < new DateTime($today_start)) {
						    $actual_start = $today_start;
						}
						
						$offset_mins = get_operational_minutes($today_start, $actual_start);
						$duration_mins = get_operational_minutes($actual_start, $s->end_date);
						
						// Total visible timeline = 10 days * 510 mins = 5100 mins
						$left_pct = ($offset_mins / 5100) * 100;
						$width_pct = ($duration_mins / 5100) * 100;
						
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
							        <div style="border-left:1px solid var(--ghost);"></div>
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
