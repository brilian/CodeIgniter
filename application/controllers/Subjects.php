<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Subjects extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Subject_model');
	}

	public function index()
	{
		$this->authorize(array('admin'));
		$data['page_title'] = 'Mata Pelajaran';
		$data['subjects'] = $this->Subject_model->all();
		$this->render('subjects/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin'));

		if ($this->input->post())
		{
			$this->_rules();
			if ($this->form_validation->run())
			{
				$this->Subject_model->create($this->_payload());
				$this->flash_success('Mapel dibuat.');
				redirect('subjects');
			}
		}

		$data['page_title'] = 'Tambah Mapel';
		$this->render('subjects/form', $data);
	}

	public function edit($id)
	{
		$this->authorize(array('admin'));
		$subject = $this->Subject_model->find($id);

		if ( ! $subject)
		{
			show_404();
		}

		if ($this->input->post())
		{
			$this->_rules();
			if ($this->form_validation->run())
			{
				$this->Subject_model->update($id, $this->_payload());
				$this->flash_success('Mapel diperbarui.');
				redirect('subjects');
			}
		}

		$data['page_title'] = 'Ubah Mapel';
		$data['subject'] = $subject;
		$this->render('subjects/form', $data);
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$this->Subject_model->delete($id);
		$this->flash_success('Mapel dihapus.');
		redirect('subjects');
	}

	private function _rules()
	{
		$this->form_validation->set_rules('name', 'Nama Mapel', 'required');
		$this->form_validation->set_rules('code', 'Kode', 'required');
		$this->form_validation->set_rules('kkm', 'KKM', 'required|integer');
	}

	private function _payload()
	{
		return array(
			'name' => $this->input->post('name'),
			'code' => $this->input->post('code'),
			'kkm' => (int) $this->input->post('kkm'),
			'description' => $this->input->post('description')
		);
	}
}
