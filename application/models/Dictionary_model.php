<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dictionary_model extends CI_Model
{
	const STORE_KEY = 'dictionary';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->seed_defaults();
	}

	public function all($filters = array())
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());
		$query   = isset($filters['search']) ? strtolower($filters['search']) : NULL;
		$category = isset($filters['category']) ? strtolower($filters['category']) : NULL;
		$difficulty = isset($filters['difficulty']) ? strtolower($filters['difficulty']) : NULL;

		$result = array_filter($entries, function ($entry) use ($query, $category, $difficulty) {
			if ($query && strpos(strtolower($entry['title']), $query) === FALSE && strpos(strtolower($entry['description']), $query) === FALSE)
			{
				return FALSE;
			}

			if ($category && strtolower($entry['category']) !== $category)
			{
				return FALSE;
			}

			if ($difficulty && strtolower($entry['difficulty']) !== $difficulty)
			{
				return FALSE;
			}

			return TRUE;
		});

		return array_values($result);
	}

	public function find_by_slug($slug)
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());

		foreach ($entries as $entry)
		{
			if ($entry['slug'] === $slug)
			{
				return $entry;
			}
		}

		return NULL;
	}

	public function create($data, $user)
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());
		$slug    = $this->slugify($data['title']);

		foreach ($entries as $entry)
		{
			if ($entry['slug'] === $slug)
			{
				throw new Exception('Entry already exists.');
			}
		}

		$new_entry = array(
			'id'          => $this->generate_id($entries),
			'title'       => $data['title'],
			'slug'        => $slug,
			'category'    => $data['category'],
			'difficulty'  => isset($data['difficulty']) ? $data['difficulty'] : 'beginner',
			'description' => $data['description'],
			'tips'        => isset($data['tips']) ? $data['tips'] : '',
			'video_url'   => isset($data['video_url']) ? $data['video_url'] : '',
			'thumb_url'   => isset($data['thumb_url']) ? $data['thumb_url'] : '',
			'status'      => isset($data['status']) ? $data['status'] : 'published',
			'created_by'  => $user['id'],
			'created_at'  => date('c'),
			'updated_at'  => date('c')
		);

		$entries[] = $new_entry;
		$this->json_store->write(self::STORE_KEY, $entries);

		return $new_entry;
	}

	public function update($slug, $data)
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());
		$found   = NULL;

		foreach ($entries as &$entry)
		{
			if ($entry['slug'] === $slug)
			{
				$entry = array_merge($entry, $data);
				$entry['updated_at'] = date('c');
				$found = $entry;
				break;
			}
		}

		if ( ! $found)
		{
			return NULL;
		}

		$this->json_store->write(self::STORE_KEY, $entries);

		return $found;
	}

	public function delete($slug)
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());
		$filtered = array();
		$deleted  = NULL;

		foreach ($entries as $entry)
		{
			if ($entry['slug'] === $slug)
			{
				$deleted = $entry;
				continue;
			}

			$filtered[] = $entry;
		}

		$this->json_store->write(self::STORE_KEY, $filtered);

		return $deleted;
	}

	protected function slugify($text)
	{
		$text = strtolower(trim($text));
		$text = preg_replace('/[^a-z0-9]+/i', '-', $text);

		return trim($text, '-');
	}

	protected function generate_id($entries)
	{
		return empty($entries) ? 1 : (max(array_column($entries, 'id')) + 1);
	}

	protected function seed_defaults()
	{
		$entries = $this->json_store->read(self::STORE_KEY, array());

		if ( ! empty($entries))
		{
			return;
		}

		$seed = array(
			array(
				'id'          => 1,
				'title'       => 'Halo',
				'slug'        => 'halo',
				'category'    => 'sapaan',
				'difficulty'  => 'beginner',
				'description' => 'Gerakan tangan kanan melambai dari depan dada.',
				'tips'        => 'Jaga pergelangan rileks, tatap lawan bicara.',
				'video_url'   => 'https://cdn.bisindo.app/videos/halo.mp4',
				'thumb_url'   => 'https://cdn.bisindo.app/thumbs/halo.jpg',
				'status'      => 'published',
				'created_by'  => 1,
				'created_at'  => date('c'),
				'updated_at'  => date('c')
			),
			array(
				'id'          => 2,
				'title'       => 'Terima Kasih',
				'slug'        => 'terima-kasih',
				'category'    => 'ungkapan',
				'difficulty'  => 'beginner',
				'description' => 'Ujung jari menyentuh dagu lalu turun ke depan.',
				'tips'        => 'Gunakan mimik wajah bersahabat.',
				'video_url'   => 'https://cdn.bisindo.app/videos/terima-kasih.mp4',
				'thumb_url'   => 'https://cdn.bisindo.app/thumbs/terima-kasih.jpg',
				'status'      => 'published',
				'created_by'  => 1,
				'created_at'  => date('c'),
				'updated_at'  => date('c')
			)
		);

		$this->json_store->write(self::STORE_KEY, $seed);
	}
}

/* End of file Dictionary_model.php */
/* Location: ./application/models/Dictionary_model.php */
