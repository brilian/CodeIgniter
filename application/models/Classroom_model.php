<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Classroom_model extends CI_Model
{
	protected $table = 'classes';

	public function all()
	{
		return $this->db
			->select('classes.*, users.name AS wali_name')
			->join('users', 'users.id = classes.wali_user_id', 'left')
			->order_by('classes.level', 'ASC')
			->order_by('classes.name', 'ASC')
			->get($this->table)
			->result_array();
	}

	public function find($id)
	{
		return $this->db
			->select('classes.*, users.name AS wali_name')
			->join('users', 'users.id = classes.wali_user_id', 'left')
			->get_where($this->table, array('classes.id' => (int) $id))
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

	public function by_wali($wali_id)
	{
		return $this->db
			->order_by('academic_year', 'DESC')
			->get_where($this->table, array('wali_user_id' => (int) $wali_id))
			->result_array();
	}
}
