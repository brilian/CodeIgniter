<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Extracurricular extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Extracurricular_model',
			'Extracurricular_score_model',
			'Classroom_model',
			'Student_model'
		));
		$this->config->load('erapor', TRUE);
	}

	public function index()
	{
		$this->authorize(array('admin', 'guru', 'wali'));

		$data['page_title'] = 'Ekstrakurikuler';
		$data['items'] = $this->Extracurricular_model->all();
		$this->render('extracurricular/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin'));

		if ($this->input->post())
		{
			$this->_rules();
			if ($this->form_validation->run())
			{
				$this->Extracurricular_model->create($this->_payload());
				$this->flash_success('Data ekstrakurikuler dibuat.');
				redirect('extracurricular');
			}
		}

		$data['page_title'] = 'Tambah Ekstrakurikuler';
		$this->render('extracurricular/form', $data);
	}

	public function edit($id)
	{
		$this->authorize(array('admin'));
		$item = $this->Extracurricular_model->find($id);

		if ( ! $item)
		{
			show_404();
		}

		if ($this->input->post())
		{
			$this->_rules();
			if ($this->form_validation->run())
			{
				$this->Extracurricular_model->update($id, $this->_payload());
				$this->flash_success('Data diperbarui.');
				redirect('extracurricular');
			}
		}

		$data['page_title'] = 'Ubah Ekstrakurikuler';
		$data['item'] = $item;
		$this->render('extracurricular/form', $data);
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$this->Extracurricular_model->delete($id);
		$this->flash_success('Data dihapus.');
		redirect('extracurricular');
	}

	public function scores($class_id = NULL)
	{
		$this->authorize(array('admin', 'guru', 'wali'));
		$config = $this->config->item('erapor');

		if ($this->input->post('scores'))
		{
			foreach ($this->input->post('scores') as $student_id => $rows)
			{
				foreach ($rows as $extracurricular_id => $payload)
				{
					if (empty($payload['predicate']))
					{
						continue;
					}

					$this->Extracurricular_score_model->upsert($extracurricular_id, $student_id, array(
						'predicate' => $payload['predicate'],
						'note' => $payload['note'],
						'academic_year' => $config['default_academic_year'],
						'semester' => $config['default_semester']
					));
				}
			}

			$this->flash_success('Nilai ekstrakurikuler disimpan.');
			redirect('extracurricular/scores?class_id='.$this->input->post('class_id'));
		}

		$data['page_title'] = 'Nilai Ekstrakurikuler';
		$data['classes'] = $this->Classroom_model->all();
		$data['selected_class'] = $class_id ?: $this->input->get('class_id');
		$data['activities'] = $this->Extracurricular_model->all();
		$data['students'] = array();

		$data['score_map'] = array();

		if ($data['selected_class'])
		{
			$data['students'] = $this->Student_model->by_class($data['selected_class']);

			$scores = $this->db
				->select('extracurricular_scores.*, students.id AS student_id')
				->join('students', 'students.id = extracurricular_scores.student_id', 'left')
				->where('students.class_id', (int) $data['selected_class'])
				->get('extracurricular_scores')
				->result_array();

			foreach ($scores as $score)
			{
				$data['score_map'][$score['student_id']][$score['extracurricular_id']] = $score;
			}
		}

		$this->render('extracurricular/scores', $data);
	}

	private function _rules()
	{
		$this->form_validation->set_rules('name', 'Nama', 'required');
	}

	private function _payload()
	{
		return array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'coach' => $this->input->post('coach')
		);
	}
}
