<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grades extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Assessment_model',
			'Grade_model',
			'Classroom_model',
			'Subject_model',
			'Student_model'
		));
		$this->config->load('erapor', TRUE);
	}

	public function index()
	{
		$this->authorize(array('admin', 'guru', 'wali'));

		$filters = array();
		if ($this->input->get('class_id'))
		{
			$filters['class_id'] = $this->input->get('class_id');
		}
		if ($this->input->get('subject_id'))
		{
			$filters['subject_id'] = $this->input->get('subject_id');
		}
		if (has_role($this->current_user, array('guru')))
		{
			$filters['teacher_id'] = $this->current_user['id'];
		}

		$data['page_title'] = 'Input Nilai';
		$data['classes'] = $this->Classroom_model->all();
		$data['subjects'] = $this->Subject_model->all();
		$data['assessments'] = $this->Assessment_model->all($filters);
		$this->render('grades/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin', 'guru', 'wali'));

		if ($this->input->post())
		{
			$this->_rules();

			if ($this->form_validation->run())
			{
				$payload = $this->_payload();
				$payload['teacher_id'] = $this->current_user['id'];
				$config = $this->config->item('erapor');
				$payload['academic_year'] = $config['default_academic_year'];
				$payload['semester'] = $config['default_semester'];
				$this->Assessment_model->create($payload);
				$this->flash_success('Penilaian dibuat. Silakan input nilai.');
				redirect('grades');
			}
		}

		$data['page_title'] = 'Buat Penilaian';
		$data['classes'] = $this->Classroom_model->all();
		$data['subjects'] = $this->Subject_model->all();
		$this->render('grades/form', $data);
	}

	public function input($assessment_id)
	{
		$this->authorize(array('admin', 'guru', 'wali'));

		$assessment = $this->Assessment_model->find($assessment_id);

		if ( ! $assessment)
		{
			show_404();
		}

		$students = $this->Student_model->by_class($assessment['class_id']);

		if ($this->input->post('scores'))
		{
			foreach ($this->input->post('scores') as $student_id => $payload)
			{
				if ($payload['score'] === '')
				{
					continue;
				}

				$score = (float) $payload['score'];
				$this->Grade_model->upsert($assessment_id, $student_id, array(
					'score' => $score,
					'predicate' => $payload['predicate'] ?: score_to_predicate($score),
					'note' => $payload['note']
				));
			}

			$this->flash_success('Nilai berhasil disimpan.');
			redirect('grades/input/'.$assessment_id);
		}

		$data['page_title'] = 'Input Nilai - '.$assessment['title'];
		$data['assessment'] = $assessment;
		$data['students'] = $students;
		$data['grades'] = $this->Grade_model->all(array('assessment_id' => $assessment_id));
		$this->render('grades/input', $data);
	}

	public function lock($assessment_id)
	{
		$this->authorize(array('admin', 'wali'));
		$this->Grade_model->lock_assessment($assessment_id);
		$this->flash_success('Nilai dikunci.');
		redirect('grades');
	}

	public function unlock($assessment_id)
	{
		$this->authorize(array('admin', 'wali'));
		$this->Grade_model->unlock_assessment($assessment_id);
		$this->flash_success('Nilai dibuka kembali.');
		redirect('grades');
	}

	private function _rules()
	{
		$this->form_validation->set_rules('title', 'Judul', 'required');
		$this->form_validation->set_rules('class_id', 'Kelas', 'required');
		$this->form_validation->set_rules('subject_id', 'Mapel', 'required');
		$this->form_validation->set_rules('type', 'Jenis', 'required');
		$this->form_validation->set_rules('due_date', 'Tanggal', 'required');
	}

	private function _payload()
	{
		return array(
			'title' => $this->input->post('title'),
			'description' => $this->input->post('description'),
			'class_id' => $this->input->post('class_id'),
			'subject_id' => $this->input->post('subject_id'),
			'type' => $this->input->post('type'),
			'weight' => $this->input->post('weight'),
			'due_date' => $this->input->post('due_date')
		);
	}
}
