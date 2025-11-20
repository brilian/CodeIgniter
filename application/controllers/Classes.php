<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Classroom_model', 'User_model'));
	}

	public function index()
	{
		$this->authorize(array('admin'));

		$data['page_title'] = 'Kelas';
		$data['classes'] = $this->Classroom_model->all();
		$this->render('classes/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin'));

		if ($this->input->post())
		{
			$this->_rules();

			if ($this->form_validation->run())
			{
				$this->Classroom_model->create($this->_payload());
				$this->flash_success('Kelas berhasil dibuat.');
				redirect('classes');
			}
		}

		$data['page_title'] = 'Tambah Kelas';
		$data['wali_candidates'] = $this->User_model->all('wali');
		$this->render('classes/form', $data);
	}

	public function edit($id)
	{
		$this->authorize(array('admin'));
		$class = $this->Classroom_model->find($id);

		if ( ! $class)
		{
			show_404();
		}

		if ($this->input->post())
		{
			$this->_rules();

			if ($this->form_validation->run())
			{
				$this->Classroom_model->update($id, $this->_payload());
				$this->flash_success('Kelas diperbarui.');
				redirect('classes');
			}
		}

		$data['page_title'] = 'Ubah Kelas';
		$data['class'] = $class;
		$data['wali_candidates'] = $this->User_model->all('wali');
		$this->render('classes/form', $data);
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$this->Classroom_model->delete($id);
		$this->flash_success('Kelas dihapus.');
		redirect('classes');
	}

	private function _rules()
	{
		$this->form_validation->set_rules('name', 'Nama Kelas', 'required');
		$this->form_validation->set_rules('level', 'Tingkat', 'required');
		$this->form_validation->set_rules('academic_year', 'Tahun Ajaran', 'required');
		$this->form_validation->set_rules('semester', 'Semester', 'required|integer');
	}

	private function _payload()
	{
		return array(
			'name' => $this->input->post('name'),
			'level' => $this->input->post('level'),
			'major' => $this->input->post('major'),
			'academic_year' => $this->input->post('academic_year'),
			'semester' => (int) $this->input->post('semester'),
			'wali_user_id' => $this->input->post('wali_user_id') ?: NULL,
			'description' => $this->input->post('description')
		);
	}
}
