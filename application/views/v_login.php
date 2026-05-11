<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invs Studio — Production System</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/style.css' ?>">
</head>

<body class="hold-transition login-page">
	<div id="login-overlay" style="display:none; position:fixed; inset:0; background:rgba(20,18,16,0.96); z-index:200; display:flex; align-items:center; justify-content:center;">
		<div style="width:380px; background: var(--coal); border:1px solid var(--ghost);">
			<div style="padding:28px 28px 20px; border-bottom:1px solid var(--ghost);">
				<div style="font-family:'Bebas Neue'; font-size:30px; color: var(--ember); letter-spacing:0.08em;">INVS STUDIO</div>
				<div style="font-size:10px; color: var(--smoke); letter-spacing:0.18em; text-transform:uppercase; margin-top:2px;">Production System</div>
			</div>
			<form action="<?php echo base_url() . 'login/aksi' ?>" method="post" style="padding:24px 28px;">
				<?php
				if (isset($_GET['alert'])) {
					if ($_GET['alert'] == 'gagal') {
						echo "
                <div class='alert alert-danger font-weight-bold text-center'>
                    Maaf! Username & Password Salah.
                </div>
                ";
					} elseif ($_GET['alert'] == "belum_login") {
						echo "
                <div class='alert alert-danger font-weight-bold text-center'>
                    Anda Harus Login Terlebih Dulu!
                </div>
                ";
					} elseif ($_GET['alert'] == "logout") {
						echo "
                <div class='alert alert-success font-weight-bold text-center'>
                    Anda Telah Logout!
                </div>
                ";
					}
				}
				?>
				<div class="form-group">
					<label class="form-label">Email Address</label>
					<input name="email" class="form-input" type="email" placeholder="you@example.com">
				</div>
				<?php echo form_error('email'); ?>
				<div class="form-group">
					<label class="form-label">Password</label>
					<input name="password" class="form-input" type="password" placeholder="••••••••">
				</div>
				<?php echo form_error('password'); ?>
				<div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
					<span style="font-size:10px; color: var(--smoke);">Forgot password?</span>
					<a href="<?php echo base_url() . 'register' ?>" style="font-size:10px; color: var(--smoke);">Register →</a>
				</div>
				<button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-top:20px;">Sign In →</button>
			</form>
		</div>
	</div>
</body>

</html>
