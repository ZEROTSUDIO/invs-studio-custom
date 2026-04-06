<!-- ═══════════════════════════════════════════════ -->
<!-- PAGE: Admin Dashboard -->
<!-- ═══════════════════════════════════════════════ -->
<div class="page active" id="page-admin-dashboard">
	<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:14px; margin-bottom:24px;">
		<div class="stat-card">
			<div class="stat-val">47</div>
			<div class="stat-label">Total Orders</div>
			<div class="stat-sub">↑ 8 this week</div>
		</div>
		<div class="stat-card" style="--before-color: #4ade80;">

			<div class="stat-val">5</div>
			<div class="stat-label">In Production</div>
			<div class="stat-sub" style="color:#4ade80;">Running now</div>
		</div>
		<div class="stat-card">

			<div class="stat-val">14</div>
			<div class="stat-label">Queued (SJF)</div>
			<div class="stat-sub" style="color:#60a5fa;">Avg 2.4 days</div>
		</div>
		<div class="stat-card">

			<div class="stat-val">28</div>
			<div class="stat-label">Completed</div>
			<div class="stat-sub" style="color:#c084fc;">This month</div>
		</div>
	</div>

	<div style="display:grid; grid-template-columns: 1fr 380px; gap:16px;">

		<!-- Recent Orders -->
		<div class="panel">
			<div class="panel-header">
				<div class="panel-title">Recent Orders</div>
				<button class="btn btn-ghost" onclick="showPage('admin-orders', null)" style="padding:5px 12px; font-size:10px;">View All →</button>
			</div>
			<table class="data-table">
				<thead>
					<tr>
						<th>Order ID</th>
						<th>Customer</th>
						<th>Items</th>
						<th>Est. Duration</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="color: var(--ember);">#ORD-031</td>
						<td>Rafi Maulana</td>
						<td>3 pcs</td>
						<td>1 day</td>
						<td><span class="badge badge-production">In Production</span></td>
					</tr>
					<tr>
						<td style="color: var(--ember);">#ORD-032</td>
						<td>Siti Rahma</td>
						<td>8 pcs</td>
						<td>2 days</td>
						<td><span class="badge badge-production">In Production</span></td>
					</tr>
					<tr>
						<td style="color: var(--ember);">#ORD-033</td>
						<td>Dimas Arya</td>
						<td>15 pcs</td>
						<td>2 days</td>
						<td><span class="badge badge-confirmed">Confirmed</span></td>
					</tr>
					<tr>
						<td style="color: var(--ember);">#ORD-034</td>
						<td>Nadia Putri</td>
						<td>60 pcs</td>
						<td>5 days</td>
						<td><span class="badge badge-pending">Pending</span></td>
					</tr>
					<tr>
						<td style="color: var(--ember);">#ORD-035</td>
						<td>Bagas Prakoso</td>
						<td>2 pcs</td>
						<td>1 day</td>
						<td><span class="badge badge-pending">Pending</span></td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- SJF Queue Summary -->
		<div class="panel">
			<div class="panel-header">
				<div class="panel-title">SJF Queue Preview</div>
				<span style="font-size:9px; color: var(--smoke); letter-spacing:0.1em;">SHORTEST FIRST</span>
			</div>
			<div style="padding: 14px; display:flex; flex-direction:column; gap:10px;">
				<div class="queue-card in-prod">
					<span class="queue-num">1</span>
					<div style="font-size:10px; color:#4ade80; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:4px;">● In Production</div>
					<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px;">Rafi Maulana</div>
					<div style="font-size:11px; color: var(--smoke);">3 pcs · 1 day</div>
					<div class="prog-track">
						<div class="prog-fill" style="width:65%; background:#4ade80;"></div>
					</div>
				</div>
				<div class="queue-card in-prod">
					<span class="queue-num">2</span>
					<div style="font-size:10px; color:#4ade80; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:4px;">● In Production</div>
					<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px;">Siti Rahma</div>
					<div style="font-size:11px; color: var(--smoke);">8 pcs · 2 days</div>
					<div class="prog-track">
						<div class="prog-fill" style="width:30%; background:#4ade80;"></div>
					</div>
				</div>
				<div class="queue-card">
					<span class="queue-num">3</span>
					<div style="font-size:10px; color: var(--smoke); text-transform:uppercase; letter-spacing:0.12em; margin-bottom:4px;">Queued</div>
					<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px;">Bagas Prakoso</div>
					<div style="font-size:11px; color: var(--smoke);">2 pcs · 1 day</div>
				</div>
				<div class="queue-card">
					<span class="queue-num">4</span>
					<div style="font-size:10px; color: var(--smoke); text-transform:uppercase; letter-spacing:0.12em; margin-bottom:4px;">Queued</div>
					<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px;">Dimas Arya</div>
					<div style="font-size:11px; color: var(--smoke);">15 pcs · 2 days</div>
				</div>
			</div>
		</div>
	</div>
</div>
