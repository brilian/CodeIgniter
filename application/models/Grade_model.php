<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grade_model extends CI_Model
{
	protected $table = 'grades';

	public function all($filters = array())
	{
		if (isset($filters['assessment_id']))
		{
			$this->db->where('grades.assessment_id', (int) $filters['assessment_id']);
		}

		if (isset($filters['student_id']))
		{
			$this->db->where('grades.student_id', (int) $filters['student_id']);
		}

		return $this->db
			->select('grades.*, students.nisn, students.full_name, assessments.title AS assessment_title')
			->join('students', 'students.id = grades.student_id', 'left')
			->join('assessments', 'assessments.id = grades.assessment_id', 'left')
			->get($this->table)
			->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
	}

	public function upsert($assessment_id, $student_id, $data)
	{
		$existing = $this->db->get_where($this->table, array(
			'assessment_id' => (int) $assessment_id,
			'student_id' => (int) $student_id
		))->row_array();

		if ($existing)
		{
			$data['updated_at'] = date('Y-m-d H:i:s');
			$this->db->where('id', (int) $existing['id'])->update($this->table, $data);
			return $existing['id'];
		}

		$data['assessment_id'] = (int) $assessment_id;
		$data['student_id'] = (int) $student_id;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function lock_assessment($assessment_id)
	{
		$this->db->where('assessment_id', (int) $assessment_id)->update($this->table, array(
			'is_locked' => 1,
			'updated_at' => date('Y-m-d H:i:s')
		));
		return $this->db->affected_rows();
	}

	public function unlock_assessment($assessment_id)
	{
		$this->db->where('assessment_id', (int) $assessment_id)->update($this->table, array(
			'is_locked' => 0,
			'updated_at' => date('Y-m-d H:i:s')
		));
		return $this->db->affected_rows();
	}

	public function summary_by_student($student_id)
	{
		return $this->db
			->select('subjects.name AS subject_name, AVG(grades.score) AS avg_score')
			->join('assessments', 'assessments.id = grades.assessment_id', 'left')
			->join('subjects', 'subjects.id = assessments.subject_id', 'left')
			->where('grades.student_id', (int) $student_id)
			->group_by('subjects.id')
			->get($this->table)
			->result_array();
	}
}
