<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	protected $table = 'users';

	public function __construct()
	{
		parent::__construct();
	}

	public function all($role = NULL)
	{
		if ($role)
		{
			$this->db->where('role', $role);
		}

		return $this->db->order_by('name', 'ASC')->get($this->table)->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
	}

	public function find_by_email($email)
	{
		return $this->db->get_where($this->table, array('email' => $email))->row_array();
	}

	public function create($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', (int) $id)->update($this->table, $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('id', (int) $id)->delete($this->table);
		return $this->db->affected_rows();
	}

	public function authenticate($email, $password)
	{
		$user = $this->find_by_email($email);

		if ($user && password_verify($password, $user['password_hash']))
		{
			return $user;
		}

		return FALSE;
	}

	public function get_authenticated_user()
	{
		$user_id = $this->session->userdata('user_id');

		if (empty($user_id))
		{
			return NULL;
		}

		return $this->find($user_id);
	}

	public function ensure_default_admin()
	{
		$admin = $this->db
			->limit(1)
			->get_where($this->table, array('role' => 'admin'))
			->row_array();

		if ($admin)
		{
			return $admin;
		}

		$default = array(
			'name' => 'Administrator',
			'email' => 'admin@erapor.test',
			'role' => 'admin',
			'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
			'status' => 'active'
		);

		$default['id'] = $this->create($default);

		return $default;
	}
}
