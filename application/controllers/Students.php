<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Students extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Student_model', 'Classroom_model', 'User_model'));
		$this->config->load('erapor', TRUE);
	}

	public function index()
	{
		$this->authorize(array('admin', 'wali'));

		$class_id = $this->input->get('class_id');

		if (has_role($this->current_user, array('wali')) && empty($class_id))
		{
			$waliClasses = $this->Classroom_model->by_wali($this->current_user['id']);
			if ($waliClasses)
			{
				$class_id = $waliClasses[0]['id'];
			}
		}

		$data['page_title'] = 'Siswa';
		$data['classes'] = $this->Classroom_model->all();
		$data['selected_class'] = $class_id;
		$data['students'] = $this->Student_model->all($class_id);
		$this->render('students/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin'));

		if ($this->input->post())
		{
			$this->_rules();

			if ($this->form_validation->run())
			{
				$user_id = $this->_ensure_student_user();
				$payload = $this->_payload();
				$payload['user_id'] = $user_id;
				$this->Student_model->create($payload);
				$this->flash_success('Siswa ditambahkan.');
				redirect('students');
			}
		}

		$data['page_title'] = 'Tambah Siswa';
		$data['classes'] = $this->Classroom_model->all();
		$this->render('students/form', $data);
	}

	public function edit($id)
	{
		$this->authorize(array('admin', 'wali'));
		$student = $this->Student_model->find($id);

		if ( ! $student)
		{
			show_404();
		}

		if ($this->input->post())
		{
			$this->_rules(FALSE);

			if ($this->form_validation->run())
			{
				$payload = $this->_payload(FALSE);
				$this->Student_model->update($id, $payload);
				$this->flash_success('Data siswa diperbarui.');
				redirect('students');
			}
		}

		$data['page_title'] = 'Ubah Siswa';
		$data['student'] = $student;
		$data['classes'] = $this->Classroom_model->all();
		$this->render('students/form', $data);
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$this->Student_model->delete($id);
		$this->flash_success('Siswa dihapus.');
		redirect('students');
	}

	public function import()
	{
		$this->authorize(array('admin'));
		$data['page_title'] = 'Import Siswa';

		if ($this->input->post() && ! empty($_FILES['file']['name']))
		{
			try {
				$spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
				$worksheet = $spreadsheet->getActiveSheet();
				$rows = $worksheet->toArray();

				foreach ($rows as $index => $row)
				{
					if ($index === 0 || empty($row[0]))
					{
						continue;
					}

					$user_id = $this->User_model->create(array(
						'name' => $row[1],
						'email' => $row[2],
						'role' => 'siswa',
						'status' => 'active',
						'password_hash' => password_hash($row[3] ?: 'siswa123', PASSWORD_BCRYPT)
					));

					$this->Student_model->create(array(
						'user_id' => $user_id,
						'full_name' => $row[1],
						'nisn' => $row[0],
						'class_id' => $this->input->post('class_id'),
						'gender' => $row[4],
						'address' => $row[5]
					));
				}

				$this->flash_success('Import siswa berhasil.');
				redirect('students');
			} catch (Exception $e) {
				$this->flash_error('Import gagal: '.$e->getMessage());
			}
		}

		$data['classes'] = $this->Classroom_model->all();
		$this->render('students/import', $data);
	}

	public function export_template()
	{
		$this->authorize(array('admin'));

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'NISN');
		$sheet->setCellValue('B1', 'Nama Lengkap');
		$sheet->setCellValue('C1', 'Email');
		$sheet->setCellValue('D1', 'Password');
		$sheet->setCellValue('E1', 'Jenis Kelamin');
		$sheet->setCellValue('F1', 'Alamat');

		$writer = new Xlsx($spreadsheet);
		$filename = 'template-import-siswa.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit;
	}

	private function _rules($is_new = TRUE)
	{
		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required');
		$this->form_validation->set_rules('nisn', 'NISN', 'required');
		$this->form_validation->set_rules('class_id', 'Kelas', 'required|integer');
		if ($is_new)
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		}
	}

	private function _payload($is_new = TRUE)
	{
		return array(
			'full_name' => $this->input->post('full_name'),
			'nisn' => $this->input->post('nisn'),
			'nis' => $this->input->post('nis'),
			'class_id' => $this->input->post('class_id'),
			'gender' => $this->input->post('gender'),
			'birth_place' => $this->input->post('birth_place'),
			'birth_date' => $this->input->post('birth_date'),
			'address' => $this->input->post('address'),
			'parent_name' => $this->input->post('parent_name')
		);
	}

	private function _ensure_student_user()
	{
		$email = $this->input->post('email');
		$user = $this->User_model->find_by_email($email);

		if ($user)
		{
			return $user['id'];
		}

		return $this->User_model->create(array(
			'name' => $this->input->post('full_name'),
			'email' => $email,
			'role' => 'siswa',
			'status' => 'active',
			'password_hash' => password_hash($this->input->post('password') ?: 'siswa123', PASSWORD_BCRYPT)
		));
	}
}
