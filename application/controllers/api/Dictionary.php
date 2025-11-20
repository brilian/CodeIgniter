<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dictionary extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dictionary_model');
	}

	public function index()
	{
		$method = $this->input->method(TRUE);

		if ($method === 'GET')
		{
			$this->list_entries();
		}
		elseif ($method === 'POST')
		{
			$this->create_entry();
		}
		else
		{
			$this->method_not_allowed(array('GET', 'POST'));
		}
	}

	public function entry($slug)
	{
		$method = $this->input->method(TRUE);

		if ($method === 'GET')
		{
			return $this->show_entry($slug);
		}

		if ($method === 'PUT' || $method === 'PATCH')
		{
			return $this->update_entry($slug);
		}

		if ($method === 'DELETE')
		{
			return $this->delete_entry($slug);
		}

		$this->method_not_allowed(array('GET', 'PUT', 'PATCH', 'DELETE'));
	}

	protected function list_entries()
	{
		$query = array(
			'search'     => $this->input->get('search'),
			'category'   => $this->input->get('category'),
			'difficulty' => $this->input->get('difficulty')
		);

		$entries = $this->Dictionary_model->all($query);

		$this->respond(array(
			'count'   => count($entries),
			'entries' => $entries
		));
	}

	protected function show_entry($slug)
	{
		$entry = $this->Dictionary_model->find_by_slug($slug);

		if ( ! $entry)
		{
			return $this->respond(array('message' => 'Entry not found'), 404);
		}

		$this->respond(array('entry' => $entry));
	}

	protected function create_entry()
	{
		$user = $this->require_auth(array('admin'));

		if ( ! $user)
		{
			return;
		}

		$data = $this->get_json_input();
		$errors = $this->validate_entry($data);

		if ($errors)
		{
			return $this->respond_validation_error($errors);
		}

		try
		{
			$entry = $this->Dictionary_model->create($data, $user);
		}
		catch (Exception $e)
		{
			return $this->respond(array('message' => $e->getMessage()), 409);
		}

		$this->respond(array('entry' => $entry), 201);
	}

	protected function update_entry($slug)
	{
		$user = $this->require_auth(array('admin'));

		if ( ! $user)
		{
			return;
		}

		$data  = $this->get_json_input();
		$entry = $this->Dictionary_model->update($slug, $data);

		if ( ! $entry)
		{
			return $this->respond(array('message' => 'Entry not found'), 404);
		}

		$this->respond(array('entry' => $entry));
	}

	protected function delete_entry($slug)
	{
		$user = $this->require_auth(array('admin'));

		if ( ! $user)
		{
			return;
		}

		$entry = $this->Dictionary_model->delete($slug);

		if ( ! $entry)
		{
			return $this->respond(array('message' => 'Entry not found'), 404);
		}

		$this->respond(array('message' => 'Entry deleted', 'entry' => $entry));
	}

	protected function validate_entry($data)
	{
		$errors = array();

		if (empty($data['title']))
		{
			$errors['title'] = 'Judul wajib diisi';
		}

		if (empty($data['category']))
		{
			$errors['category'] = 'Kategori wajib diisi';
		}

		if (empty($data['description']))
		{
			$errors['description'] = 'Deskripsi wajib diisi';
		}

		return $errors;
	}
}

/* End of file Dictionary.php */
/* Location: ./application/controllers/api/Dictionary.php */
