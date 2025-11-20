<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Games extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model');
		$this->load->model('Badge_model');
	}

	public function index()
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$this->respond(array('games' => $this->Game_model->list_games()));
	}

	public function sessions($game_code)
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		try
		{
			$session = $this->Game_model->start_session($user, $game_code);
		}
		catch (Exception $e)
		{
			return $this->respond(array('message' => $e->getMessage()), 404);
		}

		$this->respond(array('session' => $session), 201);
	}

	public function session($session_id)
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$user    = $this->require_auth();
		$session = $this->Game_model->get_session($session_id);

		if ( ! $user || ! $session || $session['user_id'] !== $user['id'])
		{
			return $this->respond(array('message' => 'Session not found'), 404);
		}

		$this->respond(array('session' => $session));
	}

	public function record_event($session_id)
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		$payload = $this->get_json_input();

		try
		{
			$session = $this->Game_model->record_event($session_id, $payload);
		}
		catch (Exception $e)
		{
			return $this->respond(array('message' => $e->getMessage()), 400);
		}

		$this->respond(array('session' => $session));
	}

	public function complete($session_id)
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		try
		{
			$session = $this->Game_model->complete_session($session_id);
		}
		catch (Exception $e)
		{
			return $this->respond(array('message' => $e->getMessage()), 400);
		}

		$xp_gain = $this->calculate_xp($session);
		$this->User_model->increment_progress($user['id'], $xp_gain, 1);

		$updated_user = $this->User_model->find($user['id']);

		if ($updated_user && isset($updated_user['password']))
		{
			unset($updated_user['password']);
		}

		$awarded = $this->Badge_model->evaluate_user($updated_user);

		$this->respond(array(
			'session' => $session,
			'xp_gain' => $xp_gain,
			'awarded_badges' => $awarded
		));
	}

	protected function calculate_xp($session)
	{
		$base       = $session['score'];
		$accuracy   = $session['accuracy'];
		$difficulty = isset($session['config']['difficulty']) ? $session['config']['difficulty'] : 'medium';

		$multiplier = 1.0;

		if ($session['game_code'] === 'gesture_coach')
		{
			$multiplier += 0.3;
		}

		if ($difficulty === 'hard')
		{
			$multiplier += 0.5;
		}

		return (int) round(($base + ($accuracy * 100)) * $multiplier);
	}
}

/* End of file Games.php */
/* Location: ./application/controllers/api/Games.php */
