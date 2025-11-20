<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $current_user = NULL;
	protected $layout_data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->current_user = $this->User_model->get_authenticated_user();

		$this->layout_data['current_user'] = $this->current_user;
		$this->layout_data['flash'] = array(
			'success' => $this->session->flashdata('success'),
			'error' => $this->session->flashdata('error'),
			'warning' => $this->session->flashdata('warning'),
		);
	}

	protected function require_login()
	{
		if (empty($this->current_user))
		{
			$this->session->set_flashdata('error', 'Silakan login untuk melanjutkan.');
			redirect('auth/login');
		}
	}

	protected function authorize($roles = array())
	{
		$this->require_login();

		if (empty($roles))
		{
			return TRUE;
		}

		if ( ! in_array($this->current_user['role'], $roles))
		{
			show_error('Anda tidak memiliki akses ke halaman ini.', 403);
		}

		return TRUE;
	}

	protected function render($view, $data = array())
	{
		$payload = array_merge($this->layout_data, $data);
		$payload['content'] = $this->load->view($view, $payload, TRUE);

		$this->load->view('layouts/main', $payload);
	}

	protected function json_response($data, $status = 200)
	{
		$this->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data))
			->_display();
		exit;
	}

	protected function flash_success($message)
	{
		$this->session->set_flashdata('success', $message);
	}

	protected function flash_error($message)
	{
		$this->session->set_flashdata('error', $message);
	}
}
