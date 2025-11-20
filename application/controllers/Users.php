<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->authorize(array('admin'));

		$data['page_title'] = 'Manajemen Pengguna';
		$data['users'] = $this->User_model->all();
		$this->render('users/index', $data);
	}

	public function create()
	{
		$this->authorize(array('admin'));

		if ($this->input->post())
		{
			$this->_set_validation_rules(TRUE);

			if ($this->form_validation->run())
			{
				$payload = $this->_collect_payload();
				$payload['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				$this->User_model->create($payload);
				$this->flash_success('Pengguna berhasil ditambahkan.');
				redirect('users');
			}
		}

		$data['page_title'] = 'Tambah Pengguna';
		$this->render('users/form', $data);
	}

	public function edit($id)
	{
		$this->authorize(array('admin'));
		$user = $this->User_model->find($id);

		if ( ! $user)
		{
			show_404();
		}

		if ($this->input->post())
		{
			$this->_set_validation_rules(FALSE);

			if ($this->form_validation->run())
			{
				$payload = $this->_collect_payload(FALSE);

				if ($this->input->post('password'))
				{
					$payload['password_hash'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				}

				$this->User_model->update($id, $payload);
				$this->flash_success('Pengguna diperbarui.');
				redirect('users');
			}
		}

		$data['page_title'] = 'Ubah Pengguna';
		$data['user'] = $user;
		$this->render('users/form', $data);
	}

	public function delete($id)
	{
		$this->authorize(array('admin'));
		$this->User_model->delete($id);
		$this->flash_success('Pengguna dihapus.');
		redirect('users');
	}

	public function reset_password($id)
	{
		$this->authorize(array('admin'));
		$new_password = substr(md5(time()), 0, 8);
		$this->User_model->update($id, array(
			'password_hash' => password_hash($new_password, PASSWORD_BCRYPT)
		));
		$this->flash_success('Password baru: '.$new_password);
		redirect('users');
	}

	private function _set_validation_rules($is_new = TRUE)
	{
		$this->form_validation->set_rules('name', 'Nama', 'required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('role', 'Role', 'required');
		if ($is_new)
		{
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		}
	}

	private function _collect_payload($is_new = TRUE)
	{
		return array(
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'role' => $this->input->post('role'),
			'status' => $this->input->post('status') ?: 'active'
		);
	}
}
