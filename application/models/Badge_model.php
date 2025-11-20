<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badge_model extends CI_Model
{
	const STORE_BADGES      = 'badges';
	const STORE_USERBADGES  = 'user_badges';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->seed_defaults();
	}

	public function all()
	{
		return $this->json_store->read(self::STORE_BADGES, array());
	}

	public function user_badges($user_id)
	{
		$all = $this->json_store->read(self::STORE_USERBADGES, array());
		$result = array();

		foreach ($all as $badge)
		{
			if ((int) $badge['user_id'] === (int) $user_id)
			{
				$result[] = $badge;
			}
		}

		return $result;
	}

	public function evaluate_user($user)
	{
		$current  = $this->user_badges($user['id']);
		$owned_ids = array_map(function ($item) {
			return $item['badge_code'];
		}, $current);

		$available = $this->all();
		$awarded   = array();

		foreach ($available as $badge)
		{
			if (in_array($badge['code'], $owned_ids))
			{
				continue;
			}

			if ($this->meets_requirement($badge, $user))
			{
				$new_badge = array(
					'user_id'    => $user['id'],
					'badge_code' => $badge['code'],
					'earned_at'  => date('c')
				);

				$awarded[] = $new_badge;
				$current[] = $new_badge;
			}
		}

		if ( ! empty($awarded))
		{
			$store = $this->json_store->read(self::STORE_USERBADGES, array());
			$this->json_store->write(self::STORE_USERBADGES, array_merge($store, $awarded));
		}

		return $awarded;
	}

	protected function meets_requirement($badge, $user)
	{
		$requirements = isset($badge['requirements']) ? $badge['requirements'] : array();

		if (isset($requirements['xp_min']) && $user['xp_total'] < $requirements['xp_min'])
		{
			return FALSE;
		}

		if (isset($requirements['streak_min']) && $user['streak'] < $requirements['streak_min'])
		{
			return FALSE;
		}

		return TRUE;
	}

	protected function seed_defaults()
	{
		$badges = $this->json_store->read(self::STORE_BADGES, array());

		if ( ! empty($badges))
		{
			return;
		}

		$seed = array(
			array(
				'code'         => 'starter',
				'name'         => 'BISINDO Starter',
				'tier'         => 'bronze',
				'description'  => 'Menyelesaikan sesi pertama.',
				'requirements' => array('xp_min' => 10)
			),
			array(
				'code'         => 'sprinter',
				'name'         => 'Sign Sprinter',
				'tier'         => 'silver',
				'description'  => 'Meraih 500 XP total.',
				'requirements' => array('xp_min' => 500)
			),
			array(
				'code'         => 'dedicated',
				'name'         => 'Gesture Dedicated',
				'tier'         => 'gold',
				'description'  => 'Streak latihan 7 hari.',
				'requirements' => array('streak_min' => 7)
			)
		);

		$this->json_store->write(self::STORE_BADGES, $seed);
		$this->json_store->write(self::STORE_USERBADGES, array());
	}
}

/* End of file Badge_model.php */
/* Location: ./application/models/Badge_model.php */
