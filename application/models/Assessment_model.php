<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_model extends CI_Model
{
	protected $table = 'assessments';

	public function all($filters = array())
	{
		if (isset($filters['class_id']))
		{
			$this->db->where('assessments.class_id', (int) $filters['class_id']);
		}

		if (isset($filters['subject_id']))
		{
			$this->db->where('assessments.subject_id', (int) $filters['subject_id']);
		}

		if (isset($filters['teacher_id']))
		{
			$this->db->where('assessments.teacher_id', (int) $filters['teacher_id']);
		}

		return $this->db
			->select('assessments.*, classes.name AS class_name, subjects.name AS subject_name')
			->join('classes', 'classes.id = assessments.class_id', 'left')
			->join('subjects', 'subjects.id = assessments.subject_id', 'left')
			->order_by('assessments.due_date', 'DESC')
			->get($this->table)
			->result_array();
	}

	public function find($id)
	{
		return $this->db
			->select('assessments.*, classes.name AS class_name, subjects.name AS subject_name')
			->join('classes', 'classes.id = assessments.class_id', 'left')
			->join('subjects', 'subjects.id = assessments.subject_id', 'left')
			->get_where($this->table, array('assessments.id' => (int) $id))
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
}
