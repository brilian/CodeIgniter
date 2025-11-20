<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Badge_model');
		$this->load->model('Game_model');
	}

	public function register()
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$data = $this->get_json_input();
		$errors = $this->validate_register($data);

		if ($errors)
		{
			return $this->respond_validation_error($errors);
		}

		try
		{
			$user  = $this->User_model->create($data);
			$token = $this->User_model->create_session_token($user['id']);
		}
		catch (Exception $e)
		{
			return $this->respond(array('message' => $e->getMessage()), 409);
		}

		$this->respond(array(
			'token' => $token,
			'user'  => $user
		), 201);
	}

	public function login()
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$data = $this->get_json_input();

		if (empty($data['email']) || empty($data['password']))
		{
			return $this->respond_validation_error(array('email' => 'Email required', 'password' => 'Password required'));
		}

		$user = $this->User_model->verify_credentials($data['email'], $data['password']);

		if ( ! $user)
		{
			return $this->respond(array('message' => 'Invalid credentials'), 401);
		}

		$token = $this->User_model->create_session_token($user['id']);

		$this->respond(array(
			'token' => $token,
			'user'  => $user
		));
	}

	public function profile()
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

		$badges   = $this->Badge_model->user_badges($user['id']);
		$sessions = $this->Game_model->get_sessions();
		$recent   = array();

		foreach (array_reverse($sessions) as $session)
		{
			if ($session['user_id'] === $user['id'])
			{
				$recent[] = $session;
			}

			if (count($recent) >= 5)
			{
				break;
			}
		}

		$this->respond(array(
			'user'    => $user,
			'badges'  => $badges,
			'sessions'=> $recent
		));
	}

	public function logout()
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

		$token = $this->get_bearer_token();

		if ($token)
		{
			$this->User_model->revoke_token($token);
		}

		$this->respond(array('message' => 'Logged out'));
	}

	public function preferences()
	{
		$method = $this->input->method(TRUE);

		if ($method !== 'PUT' && $method !== 'PATCH')
		{
			return $this->method_not_allowed(array('PUT', 'PATCH'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		$data = $this->get_json_input();
		$this->User_model->update_preferences($user['id'], $data);

		$this->respond(array('message' => 'Preferences updated', 'preferences' => $data));
	}

	protected function validate_register($data)
	{
		$errors = array();

		if (empty($data['email']) || ! filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = 'Email tidak valid';
		}

		if (empty($data['password']) || strlen($data['password']) < 6)
		{
			$errors['password'] = 'Password minimal 6 karakter';
		}

		if (empty($data['display_name']))
		{
			$errors['display_name'] = 'Nama tampilan wajib diisi';
		}

		return $errors;
	}
}

/* End of file Auth.php */
/* Location: ./application/controllers/api/Auth.php */
