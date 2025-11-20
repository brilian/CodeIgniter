<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
	const STORE_USERS    = 'users';
	const STORE_SESSIONS = 'sessions';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->seed_defaults();
	}

	/**
	 * Register new user.
	 *
	 * @param array $payload
	 * @return array
	 */
	public function create($payload)
	{
		$users = $this->json_store->read(self::STORE_USERS, array());
		$email = strtolower($payload['email']);

		foreach ($users as $user)
		{
			if ($user['email'] === $email)
			{
				throw new Exception('Email already registered.');
			}
		}

		$id = $this->generate_id($users);

		$new_user = array(
			'id'           => $id,
			'email'        => $email,
			'display_name' => $payload['display_name'],
			'role'         => isset($payload['role']) ? $payload['role'] : 'learner',
			'xp_total'     => 0,
			'streak'       => 0,
			'handedness'   => isset($payload['handedness']) ? $payload['handedness'] : 'right',
			'preferences'  => array(),
			'password'     => password_hash($payload['password'], PASSWORD_BCRYPT),
			'created_at'   => date('c')
		);

		$users[] = $new_user;
		$this->json_store->write(self::STORE_USERS, $users);

		return $this->sanitize($new_user);
	}

	public function all()
	{
		$users = $this->json_store->read(self::STORE_USERS, array());

		return array_map(array($this, 'sanitize'), $users);
	}

	public function find_by_email($email)
	{
		$users = $this->json_store->read(self::STORE_USERS, array());
		$email = strtolower($email);

		foreach ($users as $user)
		{
			if ($user['email'] === $email)
			{
				return $user;
			}
		}

		return NULL;
	}

	public function find($id)
	{
		$users = $this->json_store->read(self::STORE_USERS, array());

		foreach ($users as $user)
		{
			if ((int) $user['id'] === (int) $id)
			{
				return $user;
			}
		}

		return NULL;
	}

	public function verify_credentials($email, $password)
	{
		$user = $this->find_by_email($email);

		if ( ! $user)
		{
			return NULL;
		}

		if (password_verify($password, $user['password']))
		{
			return $this->sanitize($user);
		}

		return NULL;
	}

	public function create_session_token($user_id)
	{
		$token    = bin2hex(random_bytes(32));
		$expires  = time() + (7 * 24 * 60 * 60);
		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());

		$sessions[] = array(
			'token'      => $token,
			'user_id'    => $user_id,
			'expires_at' => $expires,
			'created_at' => date('c')
		);

		$this->json_store->write(self::STORE_SESSIONS, $sessions);

		return $token;
	}

	public function get_user_by_token($token)
	{
		if ( ! $token)
		{
			return NULL;
		}

		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());

		foreach ($sessions as $session)
		{
			if ($session['token'] === $token && $session['expires_at'] >= time())
			{
				$user = $this->find($session['user_id']);

				return $user ? $this->sanitize($user) : NULL;
			}
		}

		return NULL;
	}

	public function revoke_token($token)
	{
		$sessions = $this->json_store->read(self::STORE_SESSIONS, array());
		$filtered = array();

		foreach ($sessions as $session)
		{
			if ($session['token'] !== $token)
			{
				$filtered[] = $session;
			}
		}

		$this->json_store->write(self::STORE_SESSIONS, $filtered);
	}

	public function increment_progress($user_id, $xp_delta, $streak_delta = 0)
	{
		$users = $this->json_store->read(self::STORE_USERS, array());

		foreach ($users as &$user)
		{
			if ((int) $user['id'] === (int) $user_id)
			{
				$user['xp_total'] += $xp_delta;
				$user['streak']    = max(0, $user['streak'] + $streak_delta);
				break;
			}
		}

		$this->json_store->write(self::STORE_USERS, $users);
	}

	public function update_preferences($user_id, $preferences)
	{
		$users = $this->json_store->read(self::STORE_USERS, array());

		foreach ($users as &$user)
		{
			if ((int) $user['id'] === (int) $user_id)
			{
				$user['preferences'] = $preferences;
				break;
			}
		}

		$this->json_store->write(self::STORE_USERS, $users);
	}

	protected function sanitize($user)
	{
		unset($user['password']);

		return $user;
	}

	protected function generate_id($collection)
	{
		return empty($collection) ? 1 : (max(array_column($collection, 'id')) + 1);
	}

	protected function seed_defaults()
	{
		$users = $this->json_store->read(self::STORE_USERS, array());

		if ( ! empty($users))
		{
			return;
		}

		$seed = array(
			array(
				'id'           => 1,
				'email'        => 'admin@bisindo.app',
				'display_name' => 'Admin BISINDO',
				'role'         => 'admin',
				'xp_total'     => 0,
				'streak'       => 0,
				'handedness'   => 'right',
				'preferences'  => array(),
				'password'     => password_hash('admin123', PASSWORD_BCRYPT),
				'created_at'   => date('c')
			),
			array(
				'id'           => 2,
				'email'        => 'learner@bisindo.app',
				'display_name' => 'Pelajar Demo',
				'role'         => 'learner',
				'xp_total'     => 120,
				'streak'       => 3,
				'handedness'   => 'right',
				'preferences'  => array('theme' => 'dark'),
				'password'     => password_hash('belajar', PASSWORD_BCRYPT),
				'created_at'   => date('c')
			)
		);

		$this->json_store->write(self::STORE_USERS, $seed);
		$this->json_store->write(self::STORE_SESSIONS, array());
	}
}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */
