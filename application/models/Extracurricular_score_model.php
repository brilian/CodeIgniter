<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Extracurricular_score_model extends CI_Model
{
	protected $table = 'extracurricular_scores';

	public function all($filters = array())
	{
		if (isset($filters['student_id']))
		{
			$this->db->where('extracurricular_scores.student_id', (int) $filters['student_id']);
		}

		if (isset($filters['academic_year']))
		{
			$this->db->where('extracurricular_scores.academic_year', $filters['academic_year']);
		}

		return $this->db
			->select('extracurricular_scores.*, extracurriculars.name AS extracurricular_name')
			->join('extracurriculars', 'extracurriculars.id = extracurricular_scores.extracurricular_id', 'left')
			->order_by('extracurriculars.name', 'ASC')
			->get($this->table)
			->result_array();
	}

	public function upsert($extracurricular_id, $student_id, $data)
	{
		$existing = $this->db->get_where($this->table, array(
			'extracurricular_id' => (int) $extracurricular_id,
			'student_id' => (int) $student_id,
			'academic_year' => $data['academic_year'],
			'semester' => $data['semester']
		))->row_array();

		if ($existing)
		{
			$data['updated_at'] = date('Y-m-d H:i:s');
			$this->db->where('id', (int) $existing['id'])->update($this->table, $data);
			return $existing['id'];
		}

		$data['extracurricular_id'] = (int) $extracurricular_id;
		$data['student_id'] = (int) $student_id;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
}
