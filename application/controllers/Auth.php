<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		if ($this->current_user)
		{
			redirect('dashboard');
		}

		redirect('auth/login');
	}

	public function login()
	{
		$this->User_model->ensure_default_admin();

		if ($this->input->post())
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

			if ($this->form_validation->run())
			{
				$email = $this->input->post('email');
				$password = $this->input->post('password');
				$user = $this->User_model->authenticate($email, $password);

				if ($user && $user['status'] !== 'inactive')
				{
					$this->session->set_userdata(array(
						'user_id' => $user['id'],
						'user_role' => $user['role']
					));
					$this->User_model->update($user['id'], array('last_login' => date('Y-m-d H:i:s')));
					redirect('dashboard');
				}

				$this->flash_error('Email atau password salah, atau akun tidak aktif.');
				redirect('auth/login');
			}
		}

		$data['page_title'] = 'Masuk';
		$this->render('auth/login', $data);
	}

	public function logout()
	{
		$this->session->unset_userdata(array('user_id', 'user_role'));
		$this->session->sess_destroy();
		redirect('auth/login');
	}

	public function forgot()
	{
		$data['page_title'] = 'Lupa Password';
		$this->render('auth/forgot', $data);
	}

	public function reset()
	{
		show_error('Hubungi admin untuk reset password.', 501);
	}
}
