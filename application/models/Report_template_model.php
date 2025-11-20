<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_template_model extends CI_Model
{
	protected $table = 'report_templates';

	public function all()
	{
		return $this->db->order_by('name', 'ASC')->get($this->table)->result_array();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
	}

	public function create($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', (int) $id)->update($this->table, $data);
		return $this->db->affected_rows();
	}

	public function delete($id)
	{
		$this->db->where('id', (int) $id)->delete($this->table);
		return $this->db->affected_rows();
	}

	public function default_template()
	{
		$template = $this->db
			->order_by('is_default', 'DESC')
			->order_by('id', 'ASC')
			->limit(1)
			->get($this->table)
			->row_array();

		return $template;
	}
}
