<div class="page active">
	<!-- Filter bar -->
	<div style="display:flex; justify-content:space-between; margin-bottom:18px; align-items:center;">
		<div style="font-family:'Syne',sans-serif; font-weight:700; font-size:16px; color: var(--cream);">Manajemen Pesanan</div>
		<div style="display:flex; gap:8px;">
			<a href="<?php echo base_url('dashboard/new_order'); ?>" class="btn btn-primary">+ Pesanan Baru</a>
		</div>
	</div>

	<?php if (isset($_GET['alert'])) : ?>
		<?php if ($_GET['alert'] == 'status_updated') : ?>
			<div class="alert">
				✓ &nbsp;Status pesanan berhasil diperbarui.
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	$ci = &get_instance();
	if (!function_exists('build_sort_url')) {
		function build_sort_url($col)
		{
			$ci = &get_instance();
			$get = $ci->input->get();
			unset($get['per_page']); // reset pagination when sorting
			$order = 'asc';
			if (isset($get['sort_by']) && $get['sort_by'] == $col && isset($get['sort_order']) && $get['sort_order'] == 'asc') {
				$order = 'desc';
			}
			$get['sort_by'] = $col;
			$get['sort_order'] = $order;
			return base_url('dashboard/orders') . '?' . http_build_query($get);
		}
		function sort_icon($col)
		{
			$ci = &get_instance();
			if (isset($ci->input->get()['sort_by']) && $ci->input->get()['sort_by'] == $col) {
				return isset($ci->input->get()['sort_order']) && $ci->input->get()['sort_order'] == 'asc' ? ' &uarr;' : ' &darr;';
			}
			return '';
		}
	}
	?>
	<div class="panel">
		<div class="panel-header" style="flex-wrap:wrap; gap:16px;">
			<div class="panel-title">Semua Pesanan <span style="font-size:10px; color: var(--smoke); font-weight:400; margin-left:8px;"><?php echo $total_rows; ?> total</span></div>

			<div style="flex:1;"></div>

			<!-- Filters Form -->
			<form method="GET" action="<?php echo base_url('dashboard/orders'); ?>" style="display:flex; gap:12px; align-items:center; margin:0; flex-wrap:wrap;">
				<?php if (!empty($sort_by)): ?>
					<input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
					<input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
				<?php endif; ?>

				<div style="display:flex; background:rgba(255,255,255,0.05); border-radius:6px; padding:2px;">
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="" onchange="this.form.submit()" style="display:none;" <?php echo empty($filter_status) ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo empty($filter_status) ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Semua</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="active" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'active') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'active') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Aktif</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="pending" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'pending') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'pending') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Menunggu</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="completed" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'completed') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'completed') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Selesai</div>
					</label>
					<label style="cursor:pointer; margin:0;">
						<input type="radio" name="status" value="canceled" onchange="this.form.submit()" style="display:none;" <?php echo ($filter_status == 'canceled') ? 'checked' : ''; ?>>
						<div style="padding:6px 12px; font-size:12px; border-radius:4px; <?php echo ($filter_status == 'canceled') ? 'background:var(--ember);color:#000;' : 'color:var(--smoke);'; ?>">Dibatalkan</div>
					</label>
				</div>

				<input type="text" name="q" placeholder="Cari pesanan..." class="form-control" style="width:200px; padding:6px 12px; font-size:12px;" value="<?php echo htmlspecialchars($filter_search); ?>">
				<button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:12px;">Cari</button>
				<?php if (!empty($filter_search) || !empty($filter_status)): ?>
					<a href="<?php echo base_url('dashboard/orders'); ?>" style="font-size:12px; color:var(--smoke); text-decoration:underline;">Hapus Filter</a>
				<?php endif; ?>
			</form>
		</div>
		<div style="overflow-x:auto;">
			<table class="data-table">
				<thead>
					<tr>
						<th><a href="<?php echo build_sort_url('queue'); ?>" style="color:inherit;text-decoration:none;">Antrian #<?php echo sort_icon('queue'); ?></a></th>
						<th><a href="<?php echo build_sort_url('id'); ?>" style="color:inherit;text-decoration:none;">ID Pesanan<?php echo sort_icon('id'); ?></a></th>
						<th>Pelanggan</th>
						<th>Kategori</th>
						<th>File Desain</th>
						<th>Jumlah</th>
						<th>Est. Durasi</th>
						<th><a href="<?php echo build_sort_url('deadline'); ?>" style="color:inherit;text-decoration:none;">Tenggat<?php echo sort_icon('deadline'); ?></a></th>
						<th>Tgl Mulai</th>
						<th>Tgl Selesai</th>
						<th><a href="<?php echo build_sort_url('status'); ?>" style="color:inherit;text-decoration:none;">Status<?php echo sort_icon('status'); ?></a></th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $o) : ?>
						<?php
						$is_active = in_array($o->status, ['waiting', 'scheduled', 'in_progress']);
						$queue = ($is_active && $o->queue_position) ? sprintf('%02d', $o->queue_position) : '<span style="opacity:0.4;">—</span>';
						$queue_color = ($is_active && $o->queue_position) ? 'var(--ember)' : 'var(--smoke)';
						?>
						<tr>
							<td><span style="font-family:'Bebas Neue'; font-size:18px; color: <?php echo $queue_color; ?>;"><?php echo $queue; ?></span></td>
							<td style="color: var(--ember);"><?php echo htmlspecialchars($o->order_code); ?></td>
							<td><?php echo htmlspecialchars($o->customer_name); ?></td>
							<td><span style="font-size:11px; color:var(--smoke);"><?php echo htmlspecialchars($o->category_name ?? 'Custom'); ?></span></td>
							<td><?php echo render_design_link($o->design_file); ?></td>
							<td><?php echo $o->qty; ?> pcs</td>
							<td><?php echo format_duration($o->est_duration); ?></td>
							<td><?php echo format_order_date($o->deadline); ?></td>
							<td><?php echo format_order_date($o->start_date); ?></td>
							<td><?php echo format_order_date($o->end_date); ?></td>
							<td><?php echo render_status_badge($o->status); ?></td>
							<td style="padding: 8px 16px; text-align:right;">
								<div style="display:inline-flex; gap:6px; align-items:center;">
									<?php if ($o->status != 'done' && $o->status != 'canceled') : ?>
										<?php 
											$action_url = ''; 
											$action_label = ''; 
											$action_btn_style = '';
											$confirm_msg = '';

											if ($o->status == 'ordered') {
												$action_url = base_url('dashboard/update_status/' . $o->id . '/waiting');
												$action_label = '✓ Konfirmasi';
												$action_btn_style = 'background:rgba(59,130,246,0.12); color:#60a5fa; border:1px solid rgba(59,130,246,0.25);';
												$confirm_msg = 'Konfirmasi pesanan ini dan masukkan ke antrian?';
											} elseif ($o->status == 'scheduled') {
												$action_url = base_url('dashboard/update_status/' . $o->id . '/in_progress');
												$action_label = '▶ Produksi';
												$action_btn_style = 'background:rgba(232,160,32,0.12); color:var(--ember); border:1px solid rgba(232,160,32,0.25);';
												$confirm_msg = 'Mulai produksi pesanan ini?';
											} elseif ($o->status == 'in_progress') {
												$action_url = base_url('dashboard/update_status/' . $o->id . '/done');
												$action_label = '✓ Selesai';
												$action_btn_style = 'background:rgba(34,197,94,0.12); color:#4ade80; border:1px solid rgba(34,197,94,0.25);';
												$confirm_msg = 'Tandai pesanan ini sebagai selesai?';
											}
										?>

										<?php if ($action_url): ?>
											<form method="POST" action="<?php echo $action_url; ?>" style="margin:0;">
												<button type="submit" class="btn" style="min-width:100px; justify-content:center; padding:5px 10px; font-size:10px; <?php echo $action_btn_style; ?>" onclick="return confirm('<?php echo $confirm_msg; ?>');">
													<?php echo $action_label; ?>
												</button>
											</form>
										<?php endif; ?>

										<div class="dropdown">
											<button class="btn btn-ghost" style="padding:5px 10px; font-size:10px; border-color:rgba(255,255,255,0.1);">•••</button>
											<div class="dropdown-content">
												<a href="<?php echo base_url('dashboard/edit_order/' . $o->id); ?>">✏ Edit Detail</a>
												<form method="POST" action="<?php echo base_url('dashboard/cancel_order/' . $o->id); ?>" style="margin:0;">
													<button type="submit" onclick="return confirm('PERINGATAN: Batalkan pesanan ini?');" style="color:#f87171 !important;">✕ Batalkan Pesanan</button>
												</form>
											</div>
										</div>

									<?php elseif ($o->status == 'canceled') : ?>
										<span style="font-size:9px; color:#f87171; font-weight:600; text-transform:uppercase;">Dibatalkan</span>
									<?php else: ?>
										<span style="font-size:9px; color:#4ade80; font-weight:600; text-transform:uppercase;">Selesai</span>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>

					<?php if (count($orders) == 0): ?>
						<tr>
							<td colspan="10" style="text-align:center; padding: 24px; color: var(--smoke);">Tidak ada pesanan ditemukan.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php if (!empty($pagination)) : ?>
			<div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.05); display:flex; justify-content:flex-end;">
				<style>
					.pagination {
						display: flex;
						list-style: none;
						padding: 0;
						margin: 0;
						gap: 4px;
					}

					.pagination li a,
					.pagination li strong {
						display: block;
						padding: 6px 12px;
						font-size: 13px;
						text-decoration: none;
						color: var(--smoke);
						background: rgba(255, 255, 255, 0.05);
						border-radius: 4px;
						border: 1px solid rgba(255, 255, 255, 0.05);
					}

					.pagination li.active a,
					.pagination li.active strong {
						background: var(--ember);
						color: #000;
						border-color: var(--ember);
						font-weight: bold;
					}

					.pagination li a:hover {
						background: rgba(255, 255, 255, 0.1);
					}
				</style>
				<?php echo $pagination; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
