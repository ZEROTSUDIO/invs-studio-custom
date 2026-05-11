<?php
defined('BASEPATH') or exit('No script direct access allowed');
class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_login');
	}
	function index()
	{
		$this->load->view('v_login');
	}
	function aksi()
	{
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() != false) {
			// menangkap data username dan password dari halaman login
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$where = array(
				'email' => $email,
				'password' => md5($password)
			);
			//$this->load->model('m_login');
			// cek kesesuaian login pada table pengguna
			$cek = $this->m_login->cek_login('users', $where);
			//cek login apabila benar
			if ($cek->num_rows() > 0) {
				// ambil data pengguna yang melakukan login
				$data = $this->m_login->cek_login('users', $where)->row();
				// buat session untuk pengguna yang berhasil login
				$data_session = array(
					'id' => $data->id,
					'name' => $data->name,
					'email' => $data->email,
					'status' => 'telah_login'
				);
				$this->session->set_userdata($data_session);
				// alihkan halaman ke halaman dashboard pengguna
				redirect('dashboard');
			} else {
				redirect('login?alert=gagal');
			}
		} else {
			$this->load->view('v_login');
		}
	}
	// function register(){
	//     $this->load->view('v_sign_up');
	// }
	// public function aksi_reg()
	// {
	//     $this->form_validation->set_rules('nama',  'required');
	//     $this->form_validation->set_rules('email',  'required');
	//     $this->form_validation->set_rules('username',  'required');
	//     $this->form_validation->set_rules('password',  'required|min_length[8]');
	//     $this->form_validation->set_rules('password2',  'required|min_length[8]');
	//     if ($this->form_validation->run() != false) {
	//         $nama = $this->input->post('nama');
	//         $email = $this->input->post('email');
	//         $username = $this->input->post('username');
	//         $password = md5($this->input->post('password'));
	//         $level = $this->input->post('level');
	//         $status = $this->input->post('status');
	//         $data = array(
	//             'pengguna_nama' => $nama,
	//             'pengguna_email' => $email,
	//             'pengguna_username' => $username,
	//             'pengguna_password' => $password,
	//             'pengguna_level' => $level,
	//             'pengguna_status' => $status
	//         );
	//         $this->m_data->insert_data('pengguna', $data);
	//         redirect(base_url() . 'dashboard/pengguna');
	//     } else {
	//         $this->load->view('dashboard/v_header');
	//         $this->load->view('dashboard/v_pengguna_tambah');
	//         $this->load->view('dashboard/v_footer');
	//     }
	// }
}
