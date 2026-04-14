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

		// Pseudo-cron: Automatically update production statuses
		$this->load->model('m_schedule');
		$this->m_schedule->auto_update_statuses();

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

		// KPI stat cards
		$data['stats'] = $this->m_data->get_dashboard_stats();

		// Today's Work Order to-do list
		$data['today_work'] = $this->m_data->get_todays_work_orders();

		// Live queue preview (top 5 from full schedule)
		$this->load->model('m_schedule');
		$full_schedule        = $this->m_schedule->get_full_schedule();
		$data['queue_preview'] = array_slice($full_schedule, 0, 5);

		// Deadline alerts: orders due today or tomorrow
		$data['deadline_alerts'] = $this->m_data->get_deadline_alerts(1);

		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_index', $data);
		$this->load->view('dashboard/v_footer');
	}

	public function new_order()
	{
		$data['page_title'] = 'New Order';
		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_new_order');
		$this->load->view('dashboard/v_footer');
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
		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_customers', $data);
		$this->load->view('dashboard/v_footer');
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

	public function schedule()
	{
		$this->load->model('m_schedule');
		$data['page_title'] = 'Production Schedule';

		// Fetch raw schedules and format sizes for view
		$raw_schedules = $this->m_schedule->get_full_schedule();
		// Use working-hours logic
		$data['schedules'] = $this->_prepare_schedule2_gantt_data($raw_schedules);

		// Calculate stats from raw schedules
		$data['stats'] = $this->m_schedule->get_queue_stats($raw_schedules);

		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_schedule', $data);
	}

	public function schedule_legacy()
	{
		$this->load->model('m_schedule');
		$data['page_title'] = 'Production Schedule (24h)';

		// Fetch raw schedules and format sizes for view
		$raw_schedules = $this->m_schedule->get_full_schedule();
		// Use 24-hour logic
		$data['schedules'] = $this->_prepare_schedule_gantt_data($raw_schedules);

		// Calculate stats from raw schedules
		$data['stats'] = $this->m_schedule->get_queue_stats($raw_schedules);

		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_schedule_legacy', $data);
	}

	private function _prepare_schedule_gantt_data($schedules)
	{
		$today_start = date('Y-m-d') . ' 00:00:00';
		$formatted = [];

		foreach ($schedules as $s) {
			$actual_start = $s->start_date;
			if (new DateTime($actual_start) < new DateTime($today_start)) {
				$actual_start = $today_start;
			}

			// Calculate physical minutes to match calendar columns
			$offset_mins = (strtotime($actual_start) - strtotime($today_start)) / 60;
			$duration_mins = (strtotime($s->end_date) - strtotime($actual_start)) / 60;

			// Total visible timeline = 10 calendar days = 14400 mins
			$left_pct = ($offset_mins / 14400) * 100;
			$width_pct = ($duration_mins / 14400) * 100;

			// Skip if task starts completely outside the 10-day block
			if ($left_pct >= 100) continue;

			// Clamp width if it bleeds over
			if (($left_pct + $width_pct) > 100) {
				$width_pct = 100 - $left_pct;
			}

			// Determine color based on status
			if ($s->status == 'in_progress') {
				$s->bg = '#1a5c2a';
				$s->col = '#4ade80';
			} elseif ($s->status == 'scheduled') {
				$s->bg = '#7a4010';
				$s->col = 'var(--ember)';
			} else {
				$s->bg = 'var(--ghost)';
				$s->col = 'var(--cream)';
			}

			$s->left_pct = $left_pct;
			$s->width_pct = $width_pct;

			$formatted[] = $s;
		}

		return $formatted;
	}

	private function _get_hybrid_grid_pct($target_date_str, $grid_start_str)
	{
		$target = new DateTime($target_date_str);
		$target_day = new DateTime($target->format('Y-m-d') . ' 00:00:00');
		$start_day = new DateTime($grid_start_str . ' 00:00:00');

		if ($target_day < $start_day) return 0;

		$diff = $start_day->diff($target_day);
		$day_diff = $diff->invert ? -$diff->days : $diff->days;

		$mins = ((int)$target->format('G') * 60) + (int)$target->format('i');

		if ($mins < 510) $mins = 510;
		if ($mins > 1020) $mins = 1020;

		$col_fraction = ($mins - 510) / 510;

		return ($day_diff * 10) + ($col_fraction * 10);
	}

	private function _prepare_schedule2_gantt_data($schedules)
	{
		$grid_start_date = date('Y-m-d');
		$formatted = [];

		foreach ($schedules as $s) {
			$actual_start = $s->start_date;

			if (new DateTime($actual_start) < new DateTime($grid_start_date . ' 08:30:00')) {
				$actual_start = $grid_start_date . ' 08:30:00';
			}

			$left_pct = $this->_get_hybrid_grid_pct($actual_start, $grid_start_date);
			$end_pct = $this->_get_hybrid_grid_pct($s->end_date, $grid_start_date);

			$width_pct = $end_pct - $left_pct;
			if ($width_pct < 0) $width_pct = 0;

			if ($left_pct >= 100) continue;

			if (($left_pct + $width_pct) > 100) {
				$width_pct = 100 - $left_pct;
			}

			if ($s->status == 'in_progress') {
				$s->bg = '#1a5c2a';
				$s->col = '#4ade80';
			} elseif ($s->status == 'scheduled') {
				$s->bg = '#7a4010';
				$s->col = 'var(--ember)';
			} else {
				$s->bg = 'var(--ghost)';
				$s->col = 'var(--cream)';
			}

			$s->left_pct = $left_pct;
			$s->width_pct = $width_pct;

			$formatted[] = $s;
		}

		return $formatted;
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

		$search = $this->input->get('q');
		$status = $this->input->get('status');
		$sort_by = $this->input->get('sort_by');
		$sort_order = $this->input->get('sort_order');
		if ($sort_order != 'asc' && $sort_order != 'desc') $sort_order = 'desc';

		$allowed_sorts = [
			'id'       => 'o.id',
			'deadline' => 'o.deadline',
			'queue'    => 'ps.queue_position',
			'status'   => 'o.status'
		];
		$sort_col = isset($allowed_sorts[$sort_by]) ? $allowed_sorts[$sort_by] : 'o.id';

		// Base query preparation
		$this->db->from('orders o');
		$this->db->join('customers c', 'o.customer_id = c.id');
		$this->db->join('production_schedule ps', 'o.id = ps.order_id', 'left');

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('o.order_code', $search);
			$this->db->or_like('c.name', $search);
			$this->db->group_end();
		}

		if (!empty($status)) {
			if ($status == 'active') {
				$this->db->where_in('o.status', ['ordered', 'waiting', 'scheduled', 'in_progress']);
			} elseif ($status == 'completed') {
				$this->db->where('o.status', 'done');
			} elseif ($status == 'canceled') {
				$this->db->where('o.status', 'canceled');
			}
		}

		$total_rows = $this->db->count_all_results('', FALSE);

		// Pagination Config
		$this->load->library('pagination');
		$config['base_url'] = base_url('dashboard/orders');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'per_page';

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$limit = $config['per_page'];
		$offset = $this->input->get('per_page') ? (int) $this->input->get('per_page') : 0;

		$this->db->select('o.*, c.name as customer_name, ps.queue_position, ps.start_date, ps.end_date');

		if ($sort_col == 'ps.queue_position' && $sort_order == 'asc') {
			$this->db->order_by('ps.queue_position IS NULL', 'ASC', FALSE); // keep nulls at the bottom
			$this->db->order_by('ps.queue_position', 'ASC');
		} else {
			$this->db->order_by($sort_col, $sort_order);
		}

		$this->db->limit($limit, $offset);

		$data['orders'] = $this->db->get()->result();
		$data['total_rows'] = $total_rows;
		$data['filter_search'] = $search;
		$data['filter_status'] = $status;
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;

		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_orders', $data);
	}

	public function update_status($order_id, $new_status)
	{
		// Allowed transitions explicitly listed
		$allowed_orders_statuses = ['ordered', 'waiting', 'scheduled', 'in_progress', 'done', 'canceled'];

		if (!in_array($new_status, $allowed_orders_statuses)) {
			redirect(base_url() . 'dashboard/orders');
			return;
		}

		$this->db->trans_start();

		// Update orders table
		$this->db->where('id', $order_id)->update('orders', ['status' => $new_status]);

		// Map order statuses to production schedule statuses safely
		$schedule_status_map = [
			'ordered' => 'ordered',
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

	public function edit_order($id)
	{
		$data['page_title'] = 'Edit Order';
		$data['order'] = $this->m_data->get_order_by_id($id);

		if (!$data['order'] || $data['order']->status == 'done' || $data['order']->status == 'canceled') {
			redirect(base_url() . 'dashboard/orders?alert=invalid_action');
			return;
		}

		$data['items'] = $this->m_data->get_order_items($id);

		$this->load->view('dashboard/v_header', $data);
		$this->load->view('dashboard/v_edit_order', $data);
		$this->load->view('dashboard/v_footer');
	}

	public function update_order($id)
	{
		$order = $this->m_data->get_order_by_id($id);
		if (!$order || $order->status == 'done' || $order->status == 'canceled') {
			redirect(base_url() . 'dashboard/orders?alert=invalid_action');
			return;
		}

		$sizes = $this->input->post('size');
		$qtys  = $this->input->post('qty');

		$total_qty = 0;
		$items = array();
		if (is_array($sizes)) {
			foreach ($sizes as $i => $size) {
				if (!empty($qtys[$i]) && $qtys[$i] > 0) {
					$items[] = array(
						'size' => $size,
						'qty'  => (int) $qtys[$i],
					);
					$total_qty += (int) $qtys[$i];
				}
			}
		}

		$design_file = $order->design_file;
		if (!empty($_FILES['design_file']['name'])) {
			$config['upload_path']   = './gambar/orders/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|ai|psd|zip';
			$config['max_size']      = 10240;
			$new_filename            = uniqid() . '.' . pathinfo($_FILES['design_file']['name'], PATHINFO_EXTENSION);
			$config['file_name']     = $new_filename;
			$this->load->library('upload', $config);

			if ($this->upload->do_upload('design_file')) {
				$design_file = $this->upload->data('file_name');
			}
		}

		$est_duration = (int) $this->input->post('est_duration');

		$update_data = array(
			'product_type' => $this->input->post('product_type'),
			'qty'          => $total_qty,
			'design_file'  => $design_file,
			'notes'        => $this->input->post('notes'),
			'est_duration' => $est_duration,
			'deadline'     => $this->input->post('deadline'),
		);

		// If editing a "scheduled" or "in_progress" order and it changed in a way that disrupts queue,
		// we might need to regenerate. Actually, changing qty on waiting orders affects queue tail too!
		$needs_regen = false;
		if ($order->est_duration != $est_duration || $order->deadline != $update_data['deadline']) {
			$needs_regen = true;
		}

		$result = $this->m_data->update_order_complete($id, $update_data, $items);

		if ($result) {
			if ($needs_regen) {
				$this->m_schedule->generate();
			}
			redirect(base_url() . 'dashboard/orders?alert=order_updated');
		} else {
			redirect(base_url() . 'dashboard/edit_order/' . $id . '?alert=update_failed');
		}
	}

	public function cancel_order($id)
	{
		$order = $this->m_data->get_order_by_id($id);
		if (!$order || $order->status == 'done' || $order->status == 'canceled') {
			redirect(base_url() . 'dashboard/orders?alert=invalid_action');
			return;
		}

		$this->db->trans_start();

		$this->db->where('id', $id)->update('orders', ['status' => 'canceled']);
		$this->db->where('order_id', $id)->delete('production_schedule');

		$this->db->trans_complete();

		$this->m_schedule->generate();

		redirect(base_url() . 'dashboard/orders?alert=order_canceled');
	}
}
