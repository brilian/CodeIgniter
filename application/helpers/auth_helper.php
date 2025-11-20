<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('role_options'))
{
	function role_options()
	{
		return array(
			'admin' => 'Admin',
			'guru' => 'Guru Mapel',
			'wali' => 'Wali Kelas',
			'siswa' => 'Siswa'
		);
	}
}

if ( ! function_exists('role_label'))
{
	function role_label($role)
	{
		$options = role_options();
		return isset($options[$role]) ? $options[$role] : ucfirst($role);
	}
}

if ( ! function_exists('is_role'))
{
	function is_role($user, $role)
	{
		if (empty($user))
		{
			return FALSE;
		}

		return $user['role'] === $role;
	}
}

if ( ! function_exists('has_role'))
{
	function has_role($user, $roles = array())
	{
		if (empty($user))
		{
			return FALSE;
		}

		return in_array($user['role'], (array) $roles);
	}
}
