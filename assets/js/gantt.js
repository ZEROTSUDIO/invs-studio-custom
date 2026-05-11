/**
 * Gantt Chart Renderer — INVS Studio
 * Handles multi-view (Week / 2 Weeks / Month) production timeline.
 * Requires: window.scheduleData (set by v_schedule.php)
 */
(function () {
	'use strict';

	/* ─── Constants ───────────────────────────────────────────────── */
	var VIEW_DAYS   = { week: 7, '2weeks': 14, month: 30 };
	var DAY_MS      = 86400000;
	var LABEL_W     = 168; // px — must match .gantt-row-label width in CSS

	/* ─── Business Hours (Dynamic) ────────────────────────────── */
	var config = window.appConfig || { business_hour_start: '08:30', business_hour_end: '17:00' };
	function parseTimeMins(timeStr) {
		var p = timeStr.split(':');
		return parseInt(p[0]) * 60 + parseInt(p[1]);
	}
	var WORK_START  = parseTimeMins(config.business_hour_start);
	var WORK_END    = parseTimeMins(config.business_hour_end);
	var WORK_MINS   = WORK_END - WORK_START;

	var MONTHS  = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
	var DAYS_S  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

	/* ─── State ───────────────────────────────────────────────────── */
	var gMode       = 'week';
	var gOffset     = 0;  // days offset from today

	/* ─── Date helpers ────────────────────────────────────────────── */
	/**
	 * Safely parse a MySQL/PHP datetime string ('YYYY-MM-DD HH:MM:SS' or ISO 'YYYY-MM-DDTHH:MM:SS').
	 * Replaces space with 'T' for cross-browser compatibility.
	 */
	function parseDate(str) {
		if (!str) return null;
		var iso = str.trim().replace(' ', 'T');
		var d = new Date(iso);
		return isNaN(d.getTime()) ? null : d;
	}

	function todayMidnight() {
		var d = new Date();
		d.setHours(0, 0, 0, 0);
		return d;
	}

	function gridStart() {
		var d = todayMidnight();
		d.setDate(d.getDate() + gOffset);
		return d;
	}

	function dayCount() { return VIEW_DAYS[gMode]; }

	/**
	 * Maps a datetime string to a percentage position on the current grid.
	 * Uses working-hours compression (08:30–17:00 = 1 column unit per day).
	 */
	function dateToPct(dateStr) {
		if (!dateStr) return null;
		var dt = parseDate(dateStr);
		if (!dt) return null;
		var gs = gridStart();
		var n  = dayCount();

		var daysMid = new Date(dt);
		daysMid.setHours(0, 0, 0, 0);
		var dayOffset = Math.round((daysMid - gs) / DAY_MS);

		if (dayOffset < 0) return 0;
		if (dayOffset >= n) return 100;

		var minsInDay = dt.getHours() * 60 + dt.getMinutes();
		if (minsInDay < WORK_START) minsInDay = WORK_START;
		if (minsInDay > WORK_END)   minsInDay = WORK_END;

		var elapsed = dayOffset * WORK_MINS + (minsInDay - WORK_START);
		return (elapsed / (n * WORK_MINS)) * 100;
	}

	function fmtDuration(mins) {
		if (!mins) return '—';
		var h = Math.floor(mins / 60), m = mins % 60;
		if (h === 0) return m + 'm';
		if (m === 0) return h + 'h';
		return h + 'h ' + m + 'm';
	}

	function fmtDateTime(dateStr) {
		if (!dateStr) return '—';
		var dt = parseDate(dateStr);
		if (!dt) return '—';
		return dt.getDate() + ' ' + MONTHS[dt.getMonth()] + ' ' +
			pad(dt.getHours()) + ':' + pad(dt.getMinutes());
	}

	function fmtDate(dateStr) {
		if (!dateStr) return '—';
		// deadline is a date-only string, just parse YYYY-MM-DD
		var parts = dateStr.substring(0, 10).split('-');
		var dt = new Date(parts[0], parts[1] - 1, parts[2]);
		if (isNaN(dt)) return '—';
		return dt.getDate() + ' ' + MONTHS[dt.getMonth()];
	}

	function pad(n) { return n < 10 ? '0' + n : '' + n; }

	function isSunday(dayOffset) {
		var d = gridStart();
		d.setDate(d.getDate() + dayOffset);
		return d.getDay() === 0;
	}

	function statusColor(status) {
		if (status === 'in_progress') return { bg: '#1a5c2a', col: '#4ade80' };
		if (status === 'scheduled')   return { bg: '#7a4010', col: '#f97316' };
		return { bg: 'rgba(255,255,255,0.06)', col: 'rgba(255,255,255,0.4)' };
	}

	/* ─── DOM helpers ─────────────────────────────────────────────── */
	function el(id) { return document.getElementById(id); }

	function div(cls, styles) {
		var d = document.createElement('div');
		if (cls) d.className = cls;
		if (styles) d.style.cssText = styles;
		return d;
	}

	/* ─── Render: Date header ─────────────────────────────────────── */
	function renderHeader() {
		var header = el('gantt-header');
		header.innerHTML = '';
		var n = dayCount();
		var compact = n > 14;

		for (var i = 0; i < n; i++) {
			var d = gridStart();
			d.setDate(d.getDate() + i);
			var sun = (d.getDay() === 0);

			var html;
			if (!compact) {
				html = DAYS_S[d.getDay()] + '<br>' + d.getDate() + ' ' + MONTHS[d.getMonth()];
			} else {
				var isMonday = d.getDay() === 1;
				html = d.getDate() + (isMonday
					? '<br><span style="font-size:7px;">' + MONTHS[d.getMonth()] + '</span>'
					: '');
			}
			if (sun) html += '<br><span style="font-size:6px;opacity:0.7">LIBUR</span>';

			var col = div(
				'gantt-col-cell' + (sun ? ' gantt-col-sunday' : ''),
				'text-align:center; font-size:' + (n <= 7 ? '9px' : '8px') +
				'; letter-spacing:0.05em; padding:3px 2px; line-height:1.4;' +
				(sun ? 'color:var(--ember);' : 'color:var(--smoke);')
			);
			col.innerHTML = html;
			header.appendChild(col);
		}
	}

	/* ─── Render: Job rows ────────────────────────────────────────── */
	function renderRows() {
		var container = el('gantt-rows');
		var emptyMsg  = el('gantt-empty');
		container.innerHTML = '';

		var data = window.scheduleData || [];
		var n    = dayCount();
		var gs   = gridStart();
		var ge   = new Date(gridStart());
		ge.setDate(ge.getDate() + n);

		var visible = 0, hidden = 0;

		data.forEach(function (s) {
			var startDt = parseDate(s.start_date);
			var endDt   = parseDate(s.end_date);
			if (!startDt || !endDt) { hidden++; return; }
			if (endDt <= gs || startDt >= ge) { hidden++; return; }
			visible++;

			var left  = Math.max(0, dateToPct(s.start_date));
			var width = dateToPct(s.end_date) - left;
			if (left + width > 100) width = 100 - left;
			if (width <= 0) width = 0.8;

			var colors   = statusColor(s.status);
			var dlPct    = s.deadline ? dateToPct(s.deadline + ' 00:00:00') : null;

			/* Row */
			var row = div('gantt-row');

			var tierIcon = '';
			if (s.schedule_tier === 'urgent') tierIcon = '<span title="Mendesak (Proteksi Tenggat)" style="color:#f87171; font-size:10px; margin-right:4px;">🔥</span>';
			if (s.schedule_tier === 'quick_insert') tierIcon = '<span title="Cepat (Slot Jeda)" style="color:#e8a020; font-size:10px; margin-right:4px;">⚡</span>';

			/* Label */
			var lbl = div('gantt-row-label');
			lbl.innerHTML = tierIcon + '<span style="background:var(--ember); color:var(--ink); font-weight:700; padding:1px 5px; border-radius:2px; margin-right:8px; font-size:10px;">' + s.queue_position + '</span>' + s.order_code + ' <span style="opacity:0.6; font-size:9px;">' + s.customer_name + '</span>';
			row.appendChild(lbl);

			/* Track */
			var track = div('gantt-track');

			/* Background grid */
			var grid = div('gantt-track-grid');
			for (var i = 0; i < n; i++) {
				var cell = div('gantt-col-cell' + (isSunday(i) ? ' gantt-col-sunday' : ''));
				grid.appendChild(cell);
			}
			track.appendChild(grid);

			/* Bar */
			var bar = div('gantt-bar');
			bar.style.left  = left + '%';
			bar.style.width = width + '%';
			bar.style.background = colors.bg;
			bar.style.color = colors.col;
			bar.textContent = 'Q' + s.queue_position + ' · ' + s.qty + 'p · ' + fmtDuration(s.est_duration);

			bar.addEventListener('mouseenter', function (e) { showTooltip(e, s); });
			bar.addEventListener('mousemove',  moveTooltip);
			bar.addEventListener('mouseleave', hideTooltip);
			track.appendChild(bar);

			/* Deadline tick */
			if (dlPct !== null && dlPct >= 0 && dlPct <= 100) {
				var tick = div('gantt-deadline-tick');
				tick.style.left = dlPct + '%';
				tick.title = 'Tenggat: ' + fmtDate(s.deadline);
				track.appendChild(tick);
			}

			row.appendChild(track);
			container.appendChild(row);
		});

		/* Counters */
		el('view-jobs-count').textContent  = visible;
		el('view-jobs-hidden').textContent = hidden;

		/* Empty state */
		emptyMsg.style.display = (data.length === 0) ? 'block' : 'none';
	}

	/* ─── Render: Range label & metadata ─────────────────────────── */
	function renderMeta() {
		var gs = gridStart();
		var ge = new Date(gridStart());
		ge.setDate(ge.getDate() + dayCount() - 1);

		el('gantt-range-label').textContent =
			gs.getDate() + ' ' + MONTHS[gs.getMonth()] + ' ' + gs.getFullYear() +
			'  →  ' +
			ge.getDate() + ' ' + MONTHS[ge.getMonth()] + ' ' + ge.getFullYear();

		var modeMap = { week: 'Minggu (7h)', '2weeks': '2 Minggu (14h)', month: 'Bulan (30h)' };
		el('view-mode-label').textContent = modeMap[gMode];

		var t = todayMidnight();
		el('gantt-today-date').textContent =
			DAYS_S[t.getDay()] + ', ' + t.getDate() + ' ' + MONTHS[t.getMonth()] + ' ' + t.getFullYear();
	}

	function render() {
		renderHeader();
		renderRows();
		renderMeta();
	}

	/* ─── Tooltip ─────────────────────────────────────────────────── */
	var tooltip = null;

	function getTooltip() {
		if (!tooltip) tooltip = el('gantt-tooltip');
		return tooltip;
	}

	function showTooltip(e, s) {
		var statusLabel = {
			in_progress : '🟢 Dalam Produksi',
			scheduled   : '🟠 Antrian',
			done        : '⚫ Selesai',
			completed   : '⚫ Selesai',
		}[s.status] || s.status;

		var tierLabel = '';
		if (s.schedule_tier === 'urgent') tierLabel = '<span style="color:#f87171; font-size:9px; border:1px solid rgba(248,113,113,0.3); background:rgba(248,113,113,0.1); padding:2px 4px; border-radius:3px; margin-left:8px;">🔥 MENDESAK</span>';
		if (s.schedule_tier === 'quick_insert') tierLabel = '<span style="color:#e8a020; font-size:9px; border:1px solid rgba(232,160,32,0.3); background:rgba(232,160,32,0.1); padding:2px 4px; border-radius:3px; margin-left:8px;">⚡ CEPAT</span>';
		if (s.schedule_tier === 'normal') tierLabel = '<span style="color:var(--smoke); font-size:9px; border:1px solid rgba(255,255,255,0.1); background:rgba(255,255,255,0.05); padding:2px 4px; border-radius:3px; margin-left:8px;">SJF (NORMAL)</span>';

		var t = getTooltip();
		t.innerHTML =
			'<div style="display:flex; justify-content:space-between; align-items:flex-start;">' +
				'<div class="gantt-tooltip-title"><span style="color:var(--ember); font-weight:700; margin-right:8px;">Antrian #' + s.queue_position + '</span> · ' + s.order_code + '</div>' + 
				tierLabel +
			'</div>' +
			'<div class="gantt-tooltip-label">👤 ' + s.customer_name + '</div>' +
			'<div>' + statusLabel + '</div>' +
			'<div class="gantt-tooltip-divider"></div>' +
			'<div class="gantt-tooltip-label">🕐 Mulai</div><div>' + fmtDateTime(s.start_date) + '</div>' +
			'<div class="gantt-tooltip-label" style="margin-top:4px;">🕐 Selesai</div><div>' + fmtDateTime(s.end_date) + '</div>' +
			(s.deadline
				? '<div class="gantt-tooltip-label" style="margin-top:4px;">📅 Tenggat</div><div style="color:#f87171;">' + fmtDate(s.deadline) + '</div>'
				: '') +
			'<div class="gantt-tooltip-divider"></div>' +
			'<div class="gantt-tooltip-stats">' +
				'<div><div class="gantt-tooltip-label">Jumlah</div><div style="font-weight:600;">' + s.qty + 'p</div></div>' +
				'<div><div class="gantt-tooltip-label">Estimasi Durasi</div><div style="font-weight:600;">' + fmtDuration(s.est_duration) + '</div></div>' +
			'</div>';

		t.style.display = 'block';
		moveTooltip(e);
	}

	function moveTooltip(e) {
		var t = getTooltip();
		var tw = t.offsetWidth, th = t.offsetHeight;
		var x = e.clientX + 14, y = e.clientY + 14;
		if (x + tw > window.innerWidth  - 10) x = e.clientX - tw - 14;
		if (y + th > window.innerHeight - 10) y = e.clientY - th - 14;
		t.style.left = x + 'px';
		t.style.top  = y + 'px';
	}

	function hideTooltip() { getTooltip().style.display = 'none'; }

	/* ─── Events ──────────────────────────────────────────────────── */
	function init() {
		/* Mode toggle */
		var modeBtns = document.querySelectorAll('.gantt-mode-btn');
		modeBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				modeBtns.forEach(function (b) { b.classList.remove('active'); });
				btn.classList.add('active');
				gMode = btn.dataset.mode;
				render();
			});
		});

		/* Navigation */
		el('gantt-prev').addEventListener('click', function () {
			gOffset -= dayCount(); render();
		});
		el('gantt-next').addEventListener('click', function () {
			gOffset += dayCount(); render();
		});
		el('gantt-today').addEventListener('click', function () {
			gOffset = 0; render();
		});

		render();
	}

	/* Run after DOM is ready — only if the Gantt DOM is present on this page */
	function tryInit() {
		if (!el('gantt-rows')) return; // not the schedule page, bail out
		init();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', tryInit);
	} else {
		tryInit();
	}

})();
