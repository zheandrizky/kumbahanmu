<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->cekLogin();
		$this->load->model('model_users');
	}
	public function index()
	{
		$data['pageTitle'] = 'Dashboard';
		// $data['users'] = $this->db->get_where('users', ['username' => $this->session->userdata('username')])->row_array();
		// $data['totaladmin'] = $this->model_users->getUsers()->num_rows();
		// $data['totalguru'] = $this->model_users->getGuru()->num_rows();
		// $data['pageContent'] = $this->load->view('dashboard/main.php', $data, TRUE);
		$this->load->view('dashboard', $data);
	}
}