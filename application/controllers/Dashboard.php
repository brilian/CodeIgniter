<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'User_model',
			'Student_model',
			'Classroom_model',
			'Subject_model',
			'Assessment_model',
			'Grade_model'
		));
	}

	public function index()
	{
		$this->require_login();

		$data['page_title'] = 'Dashboard';
		$data['stats'] = array(
			'students' => $this->db->count_all_results('students'),
			'teachers' => $this->db->where('role', 'guru')->from('users')->count_all_results(),
			'classes' => $this->db->count_all_results('classes'),
			'subjects' => $this->db->count_all_results('subjects')
		);

		if (has_role($this->current_user, array('guru')))
		{
			$data['upcoming_assessments'] = $this->Assessment_model->all(array(
				'teacher_id' => $this->current_user['id']
			));
		}
		elseif (has_role($this->current_user, array('wali')))
		{
			$data['classes'] = $this->Classroom_model->by_wali($this->current_user['id']);
		}
		elseif (has_role($this->current_user, array('siswa')))
		{
			$student = $this->Student_model->find_by_user($this->current_user['id']);
			$data['student'] = $student;
			if ($student)
			{
				$data['grade_summary'] = $this->Grade_model->summary_by_student($student['id']);
			}
		}
		else
		{
			$data['recent_teachers'] = $this->User_model->all('guru');
		}

		$this->render('dashboard/index', $data);
	}
}
