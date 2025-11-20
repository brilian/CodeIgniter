<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Student_model',
			'Grade_model',
			'Extracurricular_score_model',
			'Assessment_model',
			'Subject_model'
		));
	}

	public function build_student_report($student_id, $academic_year, $semester)
	{
		$student = $this->Student_model->find($student_id);

		if ( ! $student)
		{
			return NULL;
		}

		$grades = $this->db
			->select('subjects.name AS subject_name, AVG(grades.score) AS score')
			->join('assessments', 'assessments.id = grades.assessment_id', 'left')
			->join('subjects', 'subjects.id = assessments.subject_id', 'left')
			->where('grades.student_id', (int) $student_id)
			->where('assessments.academic_year', $academic_year)
			->where('assessments.semester', (int) $semester)
			->group_by('subjects.id')
			->order_by('subjects.name', 'ASC')
			->get('grades')
			->result_array();

		$extracurriculars = $this->Extracurricular_score_model->all(array(
			'student_id' => $student_id,
			'academic_year' => $academic_year
		));

		return array(
			'student' => $student,
			'academic_year' => $academic_year,
			'semester' => $semester,
			'grades' => $grades,
			'extracurriculars' => $extracurriculars
		);
	}
}
