<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Classroom_model',
			'Student_model',
			'Subject_model'
		));
	}

	public function index()
	{
		$this->authorize(array('admin', 'guru', 'wali'));

		$class_id = $this->input->get('class_id');
		$data['classes'] = $this->Classroom_model->all();
		$data['selected_class'] = $class_id;
		$data['page_title'] = 'Rekap Nilai';
		$data['summary'] = array();
		$data['students'] = array();

		if ($class_id)
		{
			$data['students'] = $this->Student_model->by_class($class_id);
			$data['summary'] = $this->_subject_summary($class_id);
		}

		$this->render('rekap/index', $data);
	}

	private function _subject_summary($class_id)
	{
		return $this->db
			->select('subjects.name AS subject_name, AVG(grades.score) AS avg_score, COUNT(grades.id) AS total_entries')
			->join('assessments', 'assessments.id = grades.assessment_id', 'left')
			->join('subjects', 'subjects.id = assessments.subject_id', 'left')
			->where('assessments.class_id', (int) $class_id)
			->group_by('subjects.id')
			->get('grades')
			->result_array();
	}
}
