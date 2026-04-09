<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('m_login');
		$this->load->model('m_data');
		if ($this->session->userdata('status') != "telah_login") {
			redirect('login?alert=belum_login');
		}
	}

	function forbidden()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_forbidden');
		$this->load->view('dashboard/v_footer');
	}

	function index()
	{
		$data['page_title'] = 'Admin Dashboard';
		$this->load->view('v_header', $data);
		$this->load->view('v_index');
		$this->load->view('v_footer');
	}

	public function new_order()
	{
		$data['page_title'] = 'New Order';
		$this->load->view('v_header', $data);
		$this->load->view('v_new_order');
		$this->load->view('v_footer');
	}
	public function save_order()
	{
		$customer_id = (int) $this->input->post('customer_id');

		if (!$customer_id) {
			redirect(base_url() . 'dashboard/new_order?alert=no_customer');
			return;
		}

		// Generate a unique order code: ORD-YYYYMMDD-RANDOM
		$order_code = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

		// Collect size[] and qty[] arrays from POST
		$sizes = $this->input->post('size');
		$qtys  = $this->input->post('qty');

		// Calculate total qty
		$total_qty = array_sum($qtys);

		// Build order items array
		$items = array();
		foreach ($sizes as $i => $size) {
			if (!empty($qtys[$i])) {
				$items[] = array(
					'size' => $size,
					'qty'  => (int) $qtys[$i],
				);
			}
		}

		// Handle design file upload
		$design_file = null;
		if (!empty($_FILES['design_file']['name'])) {
			$config['upload_path']   = './gambar/orders/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|ai|psd|zip';
			$config['max_size']      = 10240; // 10MB
			$new_filename            = uniqid() . '.' . pathinfo($_FILES['design_file']['name'], PATHINFO_EXTENSION);
			$config['file_name']     = $new_filename;
			$this->load->library('upload', $config);

			if ($this->upload->do_upload('design_file')) {
				$design_file = $this->upload->data('file_name');
			}
		}

		// Build order data
		$est_duration = (int) $this->input->post('est_duration');
		$deadline_date = $this->input->post('deadline');

		// Deadline safety check (Backend)
		$this->load->model('m_schedule');
		$safe_deadline_data = $this->m_schedule->get_earliest_deadline($est_duration);
		
		if ($deadline_date && $safe_deadline_data['earliest_date'] && $deadline_date < $safe_deadline_data['earliest_date']) {
			redirect(base_url() . 'dashboard/new_order?alert=deadline_conflict');
			return;
		}

		$order = array(
			'order_code'   => $order_code,
			'product_type' => $this->input->post('product_type'),
			'qty'          => $total_qty,
			'design_file'  => $design_file,
			'notes'        => $this->input->post('notes'),
			'status'       => 'waiting',
			'est_duration' => $est_duration,
			'deadline'     => $deadline_date,
		);

		// Save via model (customer_id already resolved by picker)
		$result = $this->m_data->save_order($customer_id, $order, $items);

		if ($result) {
			redirect(base_url() . 'dashboard?alert=order_saved');
		} else {
			redirect(base_url() . 'dashboard/new_order?alert=save_failed');
		}
	}

	// AJAX: live customer search
	public function customer_search()
	{
		$q = $this->input->get('q');
		if (strlen($q) < 1) {
			echo json_encode([]);
			return;
		}
		$results = $this->m_data->search_customers($q);
		echo json_encode($results);
	}

	// AJAX: create new customer inline
	public function customer_create()
	{
		$name  = trim($this->input->post('name'));
		$phone = trim($this->input->post('phone'));

		if (!$name || !$phone) {
			echo json_encode(['success' => false, 'message' => 'Name and phone required']);
			return;
		}

		$id = $this->m_data->create_customer(['name' => $name, 'phone' => $phone]);
		echo json_encode(['success' => true, 'id' => $id, 'name' => $name, 'phone' => $phone]);
	}

	// Customers management page
	public function customers()
	{
		$data['page_title'] = 'Customers';
		$data['customers']  = $this->m_data->get_all_customers();
		$this->load->view('v_header', $data);
		$this->load->view('dashboard/v_customers', $data);
		$this->load->view('v_footer');
	}

	// AJAX: returns the earliest safe deadline date for a given est_duration (minutes)
	public function earliest_deadline()
	{
		$this->load->model('m_schedule');
		$est_duration = (int) $this->input->get('est_duration');

		if ($est_duration <= 0) {
			echo json_encode(['earliest_date' => '', 'error' => 'Invalid duration']);
			return;
		}

		$result = $this->m_schedule->get_earliest_deadline($est_duration);
		header('Content-Type: application/json');
		echo json_encode($result);
	}


	function keluar()
	{
		$this->session->sess_destroy();
		redirect('login');
	}


	// //pengguna

	// public function pengguna()
	// {

	// 	$data['pengguna'] = $this->m_data->get_data('pengguna')->result();
	// 	$this->load->view('dashboard/v_header');
	// 	$this->load->view('dashboard/v_pengguna', $data);
	// 	$this->load->view('dashboard/v_footer');
	// }

	// public function pengguna_tambah()
	// {
	// 	$this->load->view('dashboard/v_header');
	// 	$this->load->view('dashboard/v_pengguna_tambah');
	// 	$this->load->view('dashboard/v_footer');
	// }

	// public function pengguna_tambah_aksi()
	// {
	// 	$this->form_validation->set_rules('nama', 'Nama Pengguna', 'required');
	// 	$this->form_validation->set_rules('email', 'Email Pengguna', 'required');
	// 	$this->form_validation->set_rules('username', 'Username Pengguna', 'required');
	// 	$this->form_validation->set_rules('password', 'Password Pengguna', 'required|min_length[8]');
	// 	$this->form_validation->set_rules('level', 'Level Pengguna', 'required');
	// 	$this->form_validation->set_rules('status', 'Status Pengguna', 'required');
	// 	if ($this->form_validation->run() != false) {
	// 		$nama = $this->input->post('nama');
	// 		$email = $this->input->post('email');
	// 		$username = $this->input->post('username');
	// 		$password = md5($this->input->post('password'));
	// 		$level = $this->input->post('level');
	// 		$status = $this->input->post('status');
	// 		$data = array(
	// 			'pengguna_nama' => $nama,
	// 			'pengguna_email' => $email,
	// 			'pengguna_username' => $username,
	// 			'pengguna_password' => $password,
	// 			'pengguna_level' => $level,
	// 			'pengguna_status' => $status
	// 		);
	// 		$this->m_data->insert_data('pengguna', $data);
	// 		redirect(base_url() . 'dashboard/pengguna');
	// 	} else {
	// 		$this->load->view('dashboard/v_header');
	// 		$this->load->view('dashboard/v_pengguna_tambah');
	// 		$this->load->view('dashboard/v_footer');
	// 	}
	// }

	// public function pengguna_edit($id)
	// {
	// 	$where = array(
	// 		'pengguna_id' => $id
	// 	);
	// 	$data['pengguna'] = $this->m_data->edit_data('pengguna', $where)->result();
	// 	$this->load->view('dashboard/v_header');
	// 	$this->load->view('dashboard/v_pengguna_edit', $data);
	// 	$this->load->view('dashboard/v_footer');
	// }

	// public function pengguna_update()
	// {
	// 	//rules untuk wajib diisi
	// 	$this->form_validation->set_rules('nama', 'Nama Pengguna', 'required');
	// 	$this->form_validation->set_rules('email', 'Email Pengguna', 'required');
	// 	$this->form_validation->set_rules('username', 'Username Pengguna', 'required');
	// 	$this->form_validation->set_rules('level', 'Level Pengguna', 'required');
	// 	$this->form_validation->set_rules('status', 'Status Pengguna', 'required');
	// 	if ($this->form_validation->run() != false) {
	// 		$id = $this->input->post('id');
	// 		$nama = $this->input->post('nama');
	// 		$email = $this->input->post('email');
	// 		$username = $this->input->post('username');
	// 		$password = md5($this->input->post('password'));
	// 		$level = $this->input->post('level');
	// 		$status = $this->input->post('status');
	// 		if ($this->input->post('password') == "") {
	// 			$data = array(
	// 				'pengguna_nama' => $nama,
	// 				'pengguna_email' => $email,
	// 				'pengguna_username' => $username,
	// 				'pengguna_level' => $level,
	// 				'pengguna_status' => $status
	// 			);
	// 		} else {
	// 			$data = array(
	// 				'pengguna_nama' => $nama,
	// 				'pengguna_email' => $email,
	// 				'pengguna_username' => $username,
	// 				'pengguna_password' => $password,
	// 				'pengguna_level' => $level,
	// 				'pengguna_status' => $status
	// 			);
	// 		}
	// 		$where = array(
	// 			'pengguna_id' => $id
	// 		);
	// 		$this->m_data->update_data('pengguna', $data, $where);
	// 		redirect(base_url() . 'dashboard/pengguna');
	// 	} else {
	// 		$id = $this->input->post('id');
	// 		$where = array(
	// 			'pengguna_id' => $id
	// 		);
	// 		$data['pengguna'] = $this->m_data->get_data('pengguna', $where)->result();
	// 		$this->load->view('dashboard/v_header');
	// 		$this->load->view('dashboard/v_pengguna_edit', $data);
	// 		$this->load->view('dashboard/v_footer');
	// 	}
	// }

	// //hpus pengguna

	// public function pengguna_hapus($id)
	// {
	// 	$where = array(
	// 		'pengguna_id' => $id
	// 	);
	// 	$data['pengguna_hapus'] = $this->m_data->edit_data('pengguna', $where)->row();
	// 	$data['pengguna_lain'] = $this->db->query("SELECT * FROM pengguna WHERE pengguna_id != '$id'")->result();
	// 	$this->load->view('dashboard/v_header');
	// 	$this->load->view('dashboard/v_pengguna_hapus', $data);
	// 	$this->load->view('dashboard/v_footer');
	// }

	// public function pengguna_hapus_aksi()
	// {
	// 	$pengguna_hapus = $this->input->post('pengguna_hapus');
	// 	$pengguna_tujuan = $this->input->post('pengguna_tujuan');
	// 	$where = array(
	// 		'pengguna_id' => $pengguna_hapus
	// 	);
	// 	$w = array(
	// 		'artikel_author' => $pengguna_hapus
	// 	);
	// 	$d = array(
	// 		'artikel_author' => $pengguna_tujuan
	// 	);
	// 	$this->m_data->update_data('artikel', $d, $w);
	// 	$this->m_data->delete_data('pengguna', $where);
	// 	redirect(base_url() . 'dashboard/pengguna');
	// }

	public function schedule()
	{
		$this->load->model('m_schedule');
		$data['page_title'] = 'Production Schedule';

		// Fetch schedules
		$data['schedules'] = $this->m_schedule->get_full_schedule();
		
		// Calculate stats
		$data['stats'] = $this->m_schedule->get_queue_stats($data['schedules']);

		$this->load->view('v_header', $data);
		$this->load->view('dashboard/v_schedule', $data);
	}

	public function generate_schedule()
	{
		$this->load->model('m_schedule');
		$success = $this->m_schedule->generate();

		redirect(base_url() . 'dashboard/schedule?alert=schedule_updated');
	}

	public function orders()
	{
		$data['page_title'] = 'Orders list';

		// Fetch all orders joined with customers and schedule
		$this->db->select('o.*, c.name as customer_name, ps.queue_position, ps.start_date, ps.end_date');
		$this->db->from('orders o');
		$this->db->join('customers c', 'o.customer_id = c.id');
		$this->db->join('production_schedule ps', 'o.id = ps.order_id', 'left');
		$this->db->order_by('o.id', 'DESC');
		$data['orders'] = $this->db->get()->result();

		$this->load->view('v_header', $data);
		$this->load->view('dashboard/v_orders', $data);
	}

	public function update_status($order_id, $new_status)
	{
		// Allowed transitions explicitly listed
		$allowed_orders_statuses = ['waiting', 'scheduled', 'in_progress', 'done'];

		if (!in_array($new_status, $allowed_orders_statuses)) {
			redirect(base_url() . 'dashboard/orders');
			return;
		}

		$this->db->trans_start();

		// Update orders table
		$this->db->where('id', $order_id)->update('orders', ['status' => $new_status]);

		// Map order statuses to production schedule statuses safely
		$schedule_status_map = [
			'scheduled' => 'scheduled',
			'in_progress' => 'in_progress',
			'done' => 'completed'
		];

		if (isset($schedule_status_map[$new_status])) {
			$this->db->where('order_id', $order_id)->update('production_schedule', ['status' => $schedule_status_map[$new_status]]);
		}

		$this->db->trans_complete();

		redirect(base_url() . 'dashboard/orders?alert=status_updated');
	}
}
