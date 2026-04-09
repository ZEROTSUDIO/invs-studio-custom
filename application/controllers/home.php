<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('m_data');

		// Pseudo-cron: Automatically update production statuses
		$this->load->model('m_schedule');
		$this->m_schedule->auto_update_statuses();
	}
	public function index()
	{
		$this->load->view('v_home');
	}
	public function save_order()
	{
		// Collect customer data
		$name = trim($this->input->post('customer_name'));
		$phone = trim($this->input->post('phone'));

		// Check if customer already exists by phone
		$existing_customer = $this->m_data->get_customer_by_phone($phone);
		if ($existing_customer) {
			$customer_id = $existing_customer->id;
		} else {
			$customer_id = $this->m_data->create_customer(array('name' => $name, 'phone' => $phone));
		}

		// Generate a unique order code: ORD-YYYYMMDD-RANDOM
		$order_code = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

		// Collect size[] and qty[] arrays from POST
		$sizes = $this->input->post('size');
		$qtys  = $this->input->post('qty');

		// Calculate total qty
		$total_qty = 0;
		if (is_array($qtys)) {
		    $total_qty = array_sum($qtys);
		}

		// Build order items array
		$items = array();
		if (is_array($sizes)) {
		    foreach ($sizes as $i => $size) {
			    if (!empty($qtys[$i])) {
				    $items[] = array(
					    'size' => $size,
					    'qty'  => (int) $qtys[$i],
				    );
			    }
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
			redirect('home?alert=deadline_conflict#order'); 
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

		if ($this->m_data->save_order($customer_id, $order, $items)) {
			redirect('home?alert=success#order');
		} else {
			redirect('home?alert=save_failed#order');
		}
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

	public function success()
	{
		$this->load->view('success');
	}

	public function error()
	{
		$this->load->view('error');
	}
}
