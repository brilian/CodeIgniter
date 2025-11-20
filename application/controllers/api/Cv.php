<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cv extends Api_Controller
{
	public function evaluate()
	{
		if ($this->input->method(TRUE) !== 'POST')
		{
			return $this->method_not_allowed(array('POST'));
		}

		$user = $this->require_auth();

		if ( ! $user)
		{
			return;
		}

		$data = $this->get_json_input();

		if (empty($data['gesture_code']))
		{
			return $this->respond_validation_error(array('gesture_code' => 'Kode gestur wajib diisi'));
		}

		$accuracy = round(mt_rand(60, 100) / 100, 2);

		$feedback = $accuracy >= 0.8
			? 'Gerakan sangat baik, lanjutkan!'
			: 'Perbaiki posisi jari dan konsistensi tinggi tangan.';

		$this->respond(array(
			'user_id'      => $user['id'],
			'gesture_code' => $data['gesture_code'],
			'accuracy'     => $accuracy,
			'verdict'      => $accuracy >= 0.75 ? 'pass' : 'retry',
			'feedback'     => $feedback
		));
	}

	public function prompts()
	{
		if ($this->input->method(TRUE) !== 'GET')
		{
			return $this->method_not_allowed(array('GET'));
		}

		$this->respond(array(
			'prompts' => array(
				array('code' => 'halo', 'title' => 'Halo', 'video_url' => 'https://cdn.bisindo.app/videos/halo.mp4'),
				array('code' => 'maaf', 'title' => 'Maaf', 'video_url' => 'https://cdn.bisindo.app/videos/maaf.mp4'),
				array('code' => 'terima_kasih', 'title' => 'Terima Kasih', 'video_url' => 'https://cdn.bisindo.app/videos/terima-kasih.mp4')
			)
		));
	}
}

/* End of file Cv.php */
/* Location: ./application/controllers/api/Cv.php */
