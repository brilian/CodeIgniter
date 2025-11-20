<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model
{
	protected $table = 'students';

	public function all($class_id = NULL)
	{
		if ($class_id)
		{
			$this->db->where('students.class_id', (int) $class_id);
		}

		return $this->db
			->select('students.*, classes.name AS class_name, users.name AS user_name, users.email AS user_email')
			->join('classes', 'classes.id = students.class_id', 'left')
			->join('users', 'users.id = students.user_id', 'left')
			->order_by('students.nisn', 'ASC')
			->get($this->table)
			->result_array();
	}

	public function find($id)
	{
		return $this->db
			->select('students.*, classes.name AS class_name, users.name AS user_name, users.email AS user_email')
			->join('classes', 'classes.id = students.class_id', 'left')
			->join('users', 'users.id = students.user_id', 'left')
			->get_where($this->table, array('students.id' => (int) $id))
			->row_array();
	}

	public function find_by_user($user_id)
	{
		return $this->db
			->get_where($this->table, array('user_id' => (int) $user_id))
			->row_array();
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

	public function by_class($class_id)
	{
		return $this->all($class_id);
	}
}
