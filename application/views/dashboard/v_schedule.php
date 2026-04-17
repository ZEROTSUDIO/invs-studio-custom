<div class="page active">

	<!-- ── Top bar ───────────────────────────────────────────────── -->
	<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
		<div class="font-syne" style="font-weight:700; font-size:16px; color:var(--cream);">Production Scheduling</div>
		<form method="POST" action="<?php echo base_url('dashboard/generate_schedule'); ?>">
			<button type="submit" class="btn btn-primary" onclick="return confirm('Recalculate entire schedule using SJF algorithm?');">
				⟳ Generate Schedule
			</button>
		</form>
	</div>

	<!-- ── Alert ─────────────────────────────────────────────────── -->
	<?php if (isset($_GET['alert']) && $_GET['alert'] == 'schedule_updated') : ?>
		<div class="alert">
			⟳ &nbsp;SJF algorithm recalculated — queue reordered successfully. All start/end dates updated automatically.
		</div>
	<?php endif; ?>

	<!-- Raw schedule data for JS renderer -->
	<!-- Raw schedule data for JS renderer -->
	<script>
		window.scheduleData = <?php echo $schedule_json; ?>;
		window.appConfig = {
			business_hour_start: '<?php echo $this->m_settings->get("business_hour_start", "08:30"); ?>',
			business_hour_end: '<?php echo $this->m_settings->get("business_hour_end", "17:00"); ?>'
		};
	</script>

	<div class="gantt-layout">

		<!-- ═══════════════════════════════════════════
		     GANTT CHART PANEL
		     ═══════════════════════════════════════════ -->
		<div class="panel">
			<div class="panel-header">
				<div class="panel-title">Production Timeline</div>

				<div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
					<!-- Legend -->
					<div style="display:flex; gap:12px; font-size:10px; color:var(--smoke);">
						<span><span style="color:#4ade80;">●</span> In Production</span>
						<span><span style="color:var(--ember);">●</span> Queued</span>
						<span><span style="color:var(--smoke);">●</span> Done</span>
					</div>

					<!-- View mode toggle -->
					<div class="gantt-view-toggle">
						<button class="gantt-mode-btn active" data-mode="week">WEEK</button>
						<button class="gantt-mode-btn gantt-header-sep" data-mode="2weeks">2 WEEKS</button>
						<button class="gantt-mode-btn gantt-header-sep" data-mode="month">MONTH</button>
					</div>
				</div>
			</div>

			<div style="padding:16px 20px 20px;">

				<!-- Nav bar -->
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
					<div class="font-syne" id="gantt-range-label" style="font-size:11px; color:var(--smoke);">—</div>
					<div style="display:flex; gap:6px;">
						<button id="gantt-prev"  class="gantt-nav-btn">◀ Prev</button>
						<button id="gantt-today" class="gantt-nav-btn today">Today</button>
						<button id="gantt-next"  class="gantt-nav-btn">Next ▶</button>
					</div>
				</div>

				<!-- Gantt chart -->
				<div style="overflow-x:auto;">
					<div id="gantt-chart" style="min-width:500px;">

						<!-- Date header -->
						<div id="gantt-header" style="display:flex; margin-bottom:10px; padding-left:168px;"></div>

						<!-- Job rows wrapper -->
						<div style="position:relative;">
							<div id="gantt-rows" style="display:flex; flex-direction:column; gap:5px;"></div>
						</div>

						<!-- Empty state -->
						<div id="gantt-empty" style="display:none; font-size:11px; color:var(--smoke); padding:20px 0 4px 168px;">
							No scheduled productions. Generate schedule to populate.
						</div>

					</div>
				</div>

				<!-- Today label -->
				<div style="margin-top:10px; font-size:9px; color:var(--ember); letter-spacing:0.12em; padding-left:168px;">
					▲ TODAY — <span id="gantt-today-date">—</span>
				</div>

			</div>
		</div>

		<!-- ═══════════════════════════════════════════
		     SIDE PANELS
		     ═══════════════════════════════════════════ -->
		<div style="display:flex; flex-direction:column; gap:14px;">

			<!-- Algorithm Info -->
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Algorithm Info</div>
				</div>
				<div style="padding:16px;">
					<div style="font-size:11px; color:var(--smoke); line-height:1.7; margin-bottom:14px;">
						Shortest Job First (SJF) sorts pending orders by <span style="color:var(--ember);">deadline</span>
						(if urgent) and <span style="color:var(--ember);">est_duration</span> ascending.
						Process recalculates start dates automatically.
					</div>
					<div style="font-size:11px; padding:6px 10px; background:var(--ash); display:flex; justify-content:space-between;">
						<span style="color:var(--smoke);">Urgent threshold</span>
						<span style="color:var(--ember);">rem_days &lt;= est*1.5</span>
					</div>
				</div>
			</div>

			<!-- Queue Stats -->
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Queue Stats</div>
				</div>
				<div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Active Queue</span>
						<span style="font-size:11px; color:var(--ember); font-weight:600;"><?php echo $stats->count; ?> jobs</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Shortest wait</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->shortest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Longest wait</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->longest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Avg wait time</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->avg); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Capacity load</span>
						<span style="font-size:11px; color:<?php echo $stats->load_color; ?>;"><?php echo $stats->load; ?></span>
					</div>
				</div>
			</div>

			<!-- Timeline View card -->
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Timeline View</div>
				</div>
				<div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Current mode</span>
						<span id="view-mode-label" class="font-syne" style="font-size:11px; color:var(--cream); font-weight:600; text-transform:uppercase;">Week</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Jobs visible</span>
						<span id="view-jobs-count" style="font-size:11px; color:var(--cream);">—</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Jobs outside view</span>
						<span id="view-jobs-hidden" style="font-size:11px; color:var(--ember);">—</span>
					</div>
					<div style="font-size:10px; color:var(--smoke); line-height:1.6; border-top:1px solid var(--ghost); padding-top:10px;">
						Use <strong style="color:var(--cream);">Prev / Next</strong> to scroll the timeline.
						Switch modes to zoom in or out.
					</div>
				</div>
			</div>

		</div><!-- /side panel col -->

	</div><!-- /gantt-layout -->

</div><!-- /page -->

<!-- Gantt tooltip (singleton, rendered by gantt.js) -->
<div id="gantt-tooltip" class="gantt-tooltip"></div>
