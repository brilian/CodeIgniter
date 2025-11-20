<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboards extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Leaderboard_model');
	}

	public function index()
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$period      = $this->input->get('period') ?: 'weekly';
		$leaderboard = $this->Leaderboard_model->get($period);

		$this->respond(array(
			'period'      => $period,
			'leaderboard' => $leaderboard
		));
	}
}

/* End of file Leaderboards.php */
/* Location: ./application/controllers/api/Leaderboards.php */
