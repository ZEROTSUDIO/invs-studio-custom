<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('m_data');
	}
	public function index()
	{
		$this->load->view('v_home');
	}
	public function save_order()
	{
		$customer = array(
			'name' => $this->input->post('customer_name'),
			'phone' => $this->input->post('phone')
		);

		$order = array(
			'product_type' => $this->input->post('product_type'),
			'deadline' => $this->input->post('deadline'),
			'notes' => $this->input->post('notes'),
			'est_duration' => $this->input->post('est_duration'),
			'status' => 'pending'
		);

		$items = array();
		$sizes = $this->input->post('size');
		$qtys = $this->input->post('qty');

		for ($i = 0; $i < count($sizes); $i++) {
			$items[] = array(
				'size' => $sizes[$i],
				'qty' => $qtys[$i]
			);
		}

		if ($this->m_data->save_order($customer, $order, $items)) {
			redirect('home/success');
		} else {
			redirect('home/error');
		}
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
