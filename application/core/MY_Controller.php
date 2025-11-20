<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
}

/**
 * Base controller for JSON APIs.
 */
class Api_Controller extends MY_Controller
{
	/**
	 * @var array|null
	 */
	protected $current_user = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	/**
	 * Send JSON response.
	 *
	 * @param mixed $payload
	 * @param int   $status
	 */
	protected function respond($payload, $status = 200)
	{
		$this->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE));
	}

	/**
	 * Parse JSON body.
	 *
	 * @return array
	 */
	protected function get_json_input()
	{
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, TRUE);

		return is_array($data) ? $data : array();
	}

	/**
	 * Ensure user is authenticated.
	 *
	 * @param array $roles
	 * @return array|bool|null
	 */
	protected function require_auth($roles = array())
	{
		$user = $this->get_authenticated_user();

		if ( ! $user)
		{
			$this->respond(array('message' => 'Unauthorized'), 401);

			return FALSE;
		}

		if ( ! empty($roles) && ! in_array($user['role'], $roles))
		{
			$this->respond(array('message' => 'Forbidden'), 403);

			return FALSE;
		}

		return $user;
	}

	/**
	 * Resolve authenticated user from Authorization header.
	 *
	 * @return array|null
	 */
	protected function get_authenticated_user()
	{
		if ($this->current_user)
		{
			return $this->current_user;
		}

		$token = $this->get_bearer_token();

		if ( ! $token)
		{
			return NULL;
		}

		$user  = $this->User_model->get_user_by_token($token);

		$this->current_user = $user;

		return $user;
	}

	/**
	 * Convenience helper for 422 error responses.
	 *
	 * @param array $errors
	 */
	protected function respond_validation_error($errors = array())
	{
		$this->respond(array(
			'message' => 'Validation error',
			'errors'  => $errors
		), 422);
	}

	/**
	 * Extract bearer token from Authorization header.
	 *
	 * @return string|null
	 */
	protected function get_bearer_token()
	{
		$auth_header = $this->input->get_request_header('Authorization');

		if ( ! $auth_header || stripos($auth_header, 'Bearer ') !== 0)
		{
			return NULL;
		}

		return trim(substr($auth_header, 7));
	}

	/**
	 * Respond with 405 Method Not Allowed.
	 *
	 * @param array $allowed
	 */
	protected function method_not_allowed($allowed = array())
	{
		if ($allowed)
		{
			$this->output->set_header('Allow: '.implode(', ', $allowed));
		}

		$this->respond(array('message' => 'Method Not Allowed'), 405);
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
