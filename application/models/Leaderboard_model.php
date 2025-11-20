<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->load->model('Game_model');
		$this->load->model('User_model');
	}

	public function get($period = 'weekly', $limit = 20)
	{
		$range    = $this->resolve_period($period);
		$sessions = $this->Game_model->get_sessions();
		$totals   = array();

		foreach ($sessions as $session)
		{
			if ($session['status'] !== 'completed')
			{
				continue;
			}

			$completed = strtotime($session['completed_at']);

			if ($completed < $range['start'])
			{
				continue;
			}

			$user_id = $session['user_id'];

			if ( ! isset($totals[$user_id]))
			{
				$totals[$user_id] = array(
					'user_id'   => $user_id,
					'score'     => 0,
					'accuracy'  => 0,
					'sessions'  => 0
				);
			}

			$totals[$user_id]['score']    += $session['score'];
			$totals[$user_id]['accuracy'] += $session['accuracy'];
			$totals[$user_id]['sessions'] += 1;
		}

		$leaderboard = array();

		foreach ($totals as $row)
		{
			$user = $this->User_model->find($row['user_id']);

			if ( ! $user)
			{
				continue;
			}

			$leaderboard[] = array(
				'user_id'     => $user['id'],
				'display_name'=> $user['display_name'],
				'score'       => $row['score'],
				'avg_accuracy'=> $row['sessions'] ? round($row['accuracy'] / $row['sessions'], 2) : 0,
				'sessions'    => $row['sessions']
			);
		}

		usort($leaderboard, function ($a, $b) {
			return $b['score'] <=> $a['score'];
		});

		return array_slice($leaderboard, 0, $limit);
	}

	protected function resolve_period($period)
	{
		$now = time();

		switch ($period)
		{
			case 'daily':
				$start = strtotime('today', $now);
				break;
			case 'monthly':
				$start = strtotime(date('Y-m-01 00:00:00', $now));
				break;
			case 'weekly':
			default:
				$start = strtotime('monday this week', $now);
				break;
		}

		return array('start' => $start, 'end' => $now);
	}
}

/* End of file Leaderboard_model.php */
/* Location: ./application/models/Leaderboard_model.php */
