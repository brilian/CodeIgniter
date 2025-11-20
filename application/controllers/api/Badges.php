<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badges extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Badge_model');
	}

	public function index()
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$this->respond(array('badges' => $this->Badge_model->all()));
	}

	public function mine()
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		$this->respond(array('badges' => $this->Badge_model->user_badges($user['id'])));
	}
}

/* End of file Badges.php */
/* Location: ./application/controllers/api/Badges.php */
