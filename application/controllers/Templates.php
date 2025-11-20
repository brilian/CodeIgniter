<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_template_model');
		$this->config->load('erapor', TRUE);
	}

	public function index()
	{
		$this->authorize(array('admin'));
		$data['page_title'] = 'Template Rapor';
		$data['templates'] = $this->Report_template_model->all();
		$this->render('templates/index', $data);
	}

	public function upload()
	{
		$this->authorize(array('admin'));

		if ( ! empty($_FILES['file']['name']))
		{
			$config = $this->config->item('erapor');
			$targetDir = $config['template_upload_dir'];
			if ( ! is_dir($targetDir))
			{
				mkdir($targetDir, 0755, TRUE);
			}

			$filename = time().'_'.preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $_FILES['file']['name']);
			$targetPath = $targetDir.$filename;

			if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath))
			{
				$this->Report_template_model->create(array(
					'name' => $this->input->post('name') ?: $filename,
					'file_path' => $targetPath,
					'is_default' => $this->input->post('is_default') ? 1 : 0,
					'description' => $this->input->post('description')
				));
				$this->flash_success('Template berhasil diunggah.');
			}
			else
			{
				$this->flash_error('Gagal mengunggah template.');
			}
		}

		redirect('templates');
	}

	public function set_default($id)
	{
		$this->authorize(array('admin'));
		$this->db->update('report_templates', array('is_default' => 0));
		$this->Report_template_model->update($id, array('is_default' => 1));
		$this->flash_success('Template utama diperbarui.');
		redirect('templates');
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$template = $this->Report_template_model->find($id);
		if ($template && file_exists($template['file_path']))
		{
			@unlink($template['file_path']);
		}
		$this->Report_template_model->delete($id);
		$this->flash_success('Template dihapus.');
		redirect('templates');
	}
}
