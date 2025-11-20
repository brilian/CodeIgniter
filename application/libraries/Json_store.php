<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Json_store
 *
 * Lightweight helper to persist structured data into JSON files.
 * Intended for demo/prototyping use only (replacing a real database).
 */
class Json_store
{
	/**
	 * @var string
	 */
	protected $base_path;

	/**
	 * Json_store constructor.
	 *
	 * @param array $params
	 */
	public function __construct($params = array())
	{
		$this->base_path = isset($params['path'])
			? rtrim($params['path'], DIRECTORY_SEPARATOR)
			: rtrim(APPPATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'app';

		if ( ! is_dir($this->base_path))
		{
			mkdir($this->base_path, 0755, TRUE);
		}
	}

	/**
	 * Read JSON file into an array.
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function read($name, $default = array())
	{
		$file = $this->path_for($name);

		if ( ! file_exists($file))
		{
			return $default;
		}

		$raw = file_get_contents($file);
		$data = json_decode($raw, TRUE);

		return is_null($data) ? $default : $data;
	}

	/**
	 * Persist array/object to JSON.
	 *
	 * @param string $name
	 * @param mixed $data
	 * @return mixed
	 */
	public function write($name, $data)
	{
		$file = $this->path_for($name);
		$tmp  = $file.'.tmp';

		file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		rename($tmp, $file);

		return $data;
	}

	/**
	 * Atomically update data by providing a callback.
	 *
	 * @param string   $name
	 * @param callable $callback
	 * @param mixed    $default
	 * @return mixed
	 */
	public function update($name, callable $callback, $default = array())
	{
		$current = $this->read($name, $default);
		$next    = call_user_func($callback, $current);

		return $this->write($name, $next);
	}

	/**
	 * Resolve full path for a store key.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function path_for($name)
	{
		$sanitized = preg_replace('/[^a-z0-9\\-_]/i', '_', $name);

		return $this->base_path.DIRECTORY_SEPARATOR.$sanitized.'.json';
	}
}

/* End of file Json_store.php */
/* Location: ./application/libraries/Json_store.php */
