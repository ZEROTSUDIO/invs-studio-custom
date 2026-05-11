<div class="page active">

	<!-- ── Top bar ───────────────────────────────────────────────── -->
	<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
		<div class="font-syne" style="font-weight:700; font-size:16px; color:var(--cream);">Penjadwalan Produksi</div>
		<form method="POST" action="<?php echo base_url('dashboard/generate_schedule'); ?>">
			<button type="submit" class="btn btn-primary" onclick="return confirm('Hitung ulang seluruh jadwal menggunakan algoritma SJF?');">
				⟳ Buat Jadwal
			</button>
		</form>
	</div>

	<!-- ── Alert ─────────────────────────────────────────────────── -->
	<?php if (isset($_GET['alert']) && $_GET['alert'] == 'schedule_updated') : ?>
		<div class="alert">
			⟳ &nbsp;Algoritma SJF dihitung ulang — antrian berhasil diurutkan kembali. Semua tanggal mulai/selesai diperbarui otomatis.
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
				<div class="panel-title">Timeline Produksi</div>

				<div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
					<!-- Legend -->
					<div style="display:flex; gap:12px; font-size:10px; color:var(--smoke);">
						<span><span style="color:#4ade80;">●</span> Produksi</span>
						<span><span style="color:var(--ember);">●</span> Antrian</span>
						<span><span style="color:var(--smoke);">●</span> Selesai</span>
					</div>

					<!-- View mode toggle -->
					<div class="gantt-view-toggle">
						<button class="gantt-mode-btn active" data-mode="week">MINGGU</button>
						<button class="gantt-mode-btn gantt-header-sep" data-mode="2weeks">2 MINGGU</button>
						<button class="gantt-mode-btn gantt-header-sep" data-mode="month">BULAN</button>
					</div>
				</div>
			</div>

			<div style="padding:16px 20px 20px;">

				<!-- Nav bar -->
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
					<div class="font-syne" id="gantt-range-label" style="font-size:11px; color:var(--smoke);">—</div>
					<div style="display:flex; gap:6px;">
						<button id="gantt-prev"  class="gantt-nav-btn">◀ Sebel</button>
						<button id="gantt-today" class="gantt-nav-btn today">Hari Ini</button>
						<button id="gantt-next"  class="gantt-nav-btn">Selanj ▶</button>
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
							Tidak ada jadwal produksi. Klik 'Buat Jadwal' untuk mengisi.
						</div>

					</div>
				</div>

				<!-- Today label -->
				<div style="margin-top:10px; font-size:9px; color:var(--ember); letter-spacing:0.12em; padding-left:168px;">
					▲ HARI INI — <span id="gantt-today-date">—</span>
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
					<div class="panel-title">Info Algoritma</div>
				</div>
				<div style="padding:16px;">
					<div style="font-size:11px; color:var(--smoke); line-height:1.7; margin-bottom:14px;">
						Shortest Job First (SJF) mengurutkan pesanan tertunda berdasarkan <span style="color:var(--ember);">tenggat</span>
						(jika mendesak) dan <span style="color:var(--ember);">est_duration</span> terkecil.
						Proses menghitung ulang tanggal mulai secara otomatis.
					</div>
					<div style="font-size:11px; padding:6px 10px; background:var(--ash); display:flex; justify-content:space-between;">
						<span style="color:var(--smoke);">Ambang Batas Mendesak</span>
						<span style="color:var(--ember);">rem_days &lt;= est*1.5</span>
					</div>
				</div>
			</div>

			<!-- Queue Stats -->
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Statistik Antrian</div>
				</div>
				<div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Antrian Aktif</span>
						<span style="font-size:11px; color:var(--ember); font-weight:600;"><?php echo $stats->count; ?> pesanan</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Menunggu tersingkat</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->shortest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Menunggu terlama</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->longest ?? 0); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Rata-rata tunggu</span>
						<span style="font-size:11px; color:var(--cream);"><?php echo format_duration($stats->avg); ?></span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Beban kapasitas</span>
						<span style="font-size:11px; color:<?php echo $stats->load_color; ?>;"><?php echo $stats->load; ?></span>
					</div>
				</div>
			</div>

			<!-- Timeline View card -->
			<div class="panel">
				<div class="panel-header">
					<div class="panel-title">Tampilan Timeline</div>
				</div>
				<div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Mode saat ini</span>
						<span id="view-mode-label" class="font-syne" style="font-size:11px; color:var(--cream); font-weight:600; text-transform:uppercase;">Minggu</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Pesanan terlihat</span>
						<span id="view-jobs-count" style="font-size:11px; color:var(--cream);">—</span>
					</div>
					<div style="display:flex; justify-content:space-between;">
						<span style="font-size:11px; color:var(--smoke);">Pesanan di luar tampilan</span>
						<span id="view-jobs-hidden" style="font-size:11px; color:var(--ember);">—</span>
					</div>
					<div style="font-size:10px; color:var(--smoke); line-height:1.6; border-top:1px solid var(--ghost); padding-top:10px;">
						Gunakan <strong style="color:var(--cream);">Sebel / Selanj</strong> untuk menggeser timeline.
						Ganti mode untuk memperbesar atau memperkecil.
					</div>
				</div>
			</div>

		</div><!-- /side panel col -->

	</div><!-- /gantt-layout -->

</div><!-- /page -->

<!-- Gantt tooltip (singleton, rendered by gantt.js) -->
<div id="gantt-tooltip" class="gantt-tooltip"></div>
