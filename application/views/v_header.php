<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invs Studio — Production System</title>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php echo base_url(); ?>assets/img/favicon/site.webmanifest">
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/style.css' ?>">
	<!-- SIDEBAR -->
	<div class="sidebar">
		<div class="sidebar-logo">
			<img src="<?php echo base_url(); ?>assets/img/favicon/android-chrome-192x192.png" width="40" height="40" alt="Logo" style="margin-bottom:8px;">
			<div class="brand">INVS STUDIO</div>
			<div class="sub">Production System v1.0</div>
		</div>

		<!-- Role toggle
		<div style="padding: 14px 18px 0;">
			<div class="form-label">Viewing as</div>
			<div class="role-toggle">
				<button class="role-btn active" onclick="setRole('admin', this)">Admin</button>
				 <button class="role-btn" onclick="setRole('customer', this)">Customer</button> 
			</div>
		</div> -->

		<!-- Admin Nav -->
		<div class="nav-section" id="nav-admin">
			<div class="nav-label">Admin Panel</div>
			<a href="<?php echo base_url() . 'dashboard' ?>" class="nav-item <?php echo ($this->uri->segment(2) == "" || $this->uri->segment(2) == "index") ? 'active' : ''; ?>">
				<span class="icon">▦</span> Dashboard
			</a>
			<a href="<?php echo base_url() . 'dashboard/new_order' ?>" class="nav-item <?php echo ($this->uri->segment(2) == "new_order") ? 'active' : ''; ?>">
				<span class="icon">+</span> New Order
			</a>
			<a href="<?php echo base_url() . 'dashboard/orders' ?>" class="nav-item <?php echo ($this->uri->segment(2) == "orders") ? 'active' : ''; ?>">
				<span class="icon">≡</span> Orders
			</a>
			<a href="<?php echo base_url() . 'dashboard/schedule' ?>" class="nav-item">
				<span class="icon">◫</span> Schedule
			</a>
			<div class="nav-label" style="margin-top:12px;">Reports</div>
			<a href="<?php echo base_url() . 'dashboard/analytics' ?>" class="nav-item">
				<span class="icon">↗</span> Analytics
			</a>
		</div>

		<!-- Customer Nav
		<div class="nav-section" id="nav-customer" style="display:none;">
			<div class="nav-label">My Account</div>
			<div class="nav-item" onclick="showPage('cust-dashboard', this)">
				<span class="icon">▦</span> Dashboard
			</div>
			<div class="nav-item" onclick="showPage('cust-order', this)">
				<span class="icon">+</span> New Order
			</div>
			<div class="nav-item" onclick="showPage('cust-track', this)">
				<span class="icon">◎</span> Track Orders
			</div>
		</div> -->

		<div class="sidebar-footer">
			<div style="font-size:9px; color: var(--smoke); letter-spacing:0.15em; text-transform:uppercase; margin-bottom:8px;">SJF Algorithm</div>
			<div style="font-size:11px; color: var(--ember);">⟳ Queue Active</div>
			<div style="font-size:10px; color: var(--smoke); margin-top:4px;">14 orders in queue</div>
		</div>
	</div>

	<!-- MAIN -->
	<div class="main">

		<!-- TOPBAR -->
		<div class="topbar">
			<div class="page-title" id="topbar-title"><?php echo isset($page_title) ? $page_title : 'Admin Dashboard'; ?></div>
			<div style="display:flex; align-items:center; gap:16px;">
				<div style="font-size:11px; color: var(--smoke);">
					<span style="color: var(--ember);">●</span> &nbsp;Thu, 3 Apr 2025 &nbsp;|&nbsp; 09:41 WIB
				</div>
				<div style="display:flex; align-items:center; gap:8px;">
					<div style="width:30px; height:30px; background: var(--ember); display:flex; align-items:center; justify-content:center; font-family:'Bebas Neue'; font-size:15px; color: var(--ink);">A</div>
					<div>
						<div style="font-size:11px; color: var(--cream); font-weight:600;"><?php echo $this->session->userdata('name'); ?></div>
						<div style="font-size:9px; color: var(--smoke);"><?php echo $this->session->userdata('email'); ?></div>
					</div>
				</div>
			</div>
		</div>
