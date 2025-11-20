<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_model extends CI_Model
{
	const STORE_GAMES    = 'games';
	const STORE_SESSIONS = 'game_sessions';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->seed_defaults();
	}

	public function list_games()
	{
		return $this->json_store->read(self::STORE_GAMES, array());
	}

	public function start_session($user, $game_code)
	{
		$game = $this->find_game($game_code);

		if ( ! $game)
		{
			throw new Exception('Game not found.');
		}

		$session = array(
			'id'          => $this->generate_session_id(),
			'user_id'     => $user['id'],
			'game_code'   => $game_code,
			'status'      => 'active',
			'score'       => 0,
			'accuracy'    => 0,
			'events'      => array(),
			'started_at'  => date('c'),
			'completed_at'=> NULL,
			'config'      => $game['config']
		);

		$sessions   = $this->json_store->read(self::STORE_SESSIONS, array());
		$sessions[] = $session;
		$this->json_store->write(self::STORE_SESSIONS, $sessions);

		return $session;
	}

	public function get_session($session_id)
	{
		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());

		foreach ($sessions as $session)
		{
			if ($session['id'] === $session_id)
			{
				return $session;
			}
		}

		return NULL;
	}

	public function record_event($session_id, $payload)
	{
		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());
		$updated  = NULL;

		foreach ($sessions as &$session)
		{
			if ($session['id'] === $session_id)
			{
				if ($session['status'] !== 'active')
				{
					throw new Exception('Session not active.');
				}

				$event = array(
					'id'         => uniqid('evt_', TRUE),
					'type'       => isset($payload['type']) ? $payload['type'] : 'answer',
					'prompt'     => isset($payload['prompt']) ? $payload['prompt'] : '',
					'user_input' => isset($payload['user_input']) ? $payload['user_input'] : '',
					'is_correct' => (bool) isset($payload['is_correct']) ? $payload['is_correct'] : FALSE,
					'cv_score'   => isset($payload['cv_score']) ? (float) $payload['cv_score'] : NULL,
					'points'     => isset($payload['points']) ? (int) $payload['points'] : 0,
					'created_at' => date('c')
				);

				$session['events'][] = $event;
				$session['score']    += $event['points'];

				$accuracies = array();
				foreach ($session['events'] as $evt)
				{
					if ($evt['cv_score'] !== NULL)
					{
						$accuracies[] = $evt['cv_score'];
					}
					else
					{
						$accuracies[] = $evt['is_correct'] ? 1 : 0;
					}
				}

				$session['accuracy'] = empty($accuracies) ? 0 : round(array_sum($accuracies) / count($accuracies), 2);
				$updated             = $session;
				break;
			}
		}

		if ( ! $updated)
		{
			throw new Exception('Session not found.');
		}

		$this->json_store->write(self::STORE_SESSIONS, $sessions);

		return $updated;
	}

	public function complete_session($session_id)
	{
		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());
		$updated  = NULL;

		foreach ($sessions as &$session)
		{
			if ($session['id'] === $session_id)
			{
				$session['status']      = 'completed';
				$session['completed_at'] = date('c');
				$updated = $session;
				break;
			}
		}

		if ( ! $updated)
		{
			throw new Exception('Session not found.');
		}

		$this->json_store->write(self::STORE_SESSIONS, $sessions);

		return $updated;
	}

	public function get_sessions()
	{
		return $this->json_store->read(self::STORE_SESSIONS, array());
	}

	protected function find_game($code)
	{
		$games = $this->json_store->read(self::STORE_GAMES, array());

		foreach ($games as $game)
		{
			if ($game['code'] === $code)
			{
				return $game;
			}
		}

		return NULL;
	}

	protected function generate_session_id()
	{
		return uniqid('sess_', TRUE);
	}

	protected function seed_defaults()
	{
		$games = $this->json_store->read(self::STORE_GAMES, array());

		if ( ! empty($games))
		{
			return;
		}

		$seed_games = array(
			array(
				'code'        => 'sign_sprint',
				'name'        => 'Sign Sprint',
				'mode'        => 'multiple_choice',
				'description' => 'Jawab arti gerakan secepat mungkin.',
				'config'      => array(
					'time_limit' => 60,
					'question_count' => 10
				)
			),
			array(
				'code'        => 'gesture_coach',
				'name'        => 'Gesture Coach',
				'mode'        => 'computer_vision',
				'description' => 'Tiru gestur menggunakan kamera, dapatkan skor CV.',
				'config'      => array(
					'camera_required' => TRUE,
					'target_accuracy' => 0.8
				)
			),
			array(
				'code'        => 'finger_spell',
				'name'        => 'Finger Spell Builder',
				'mode'        => 'spelling',
				'description' => 'Eja kata menggunakan alfabet BISINDO.',
				'config'      => array(
					'word_pool' => array('INDONESIA', 'TEMAN', 'BELAJAR')
				)
			)
		);

		$this->json_store->write(self::STORE_GAMES, $seed_games);
		$this->json_store->write(self::STORE_SESSIONS, array());
	}
}

/* End of file Game_model.php */
/* Location: ./application/models/Game_model.php */
