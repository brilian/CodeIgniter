<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('erapor_grade_scale'))
{
	function erapor_grade_scale()
	{
		$CI =& get_instance();
		$CI->config->load('erapor', TRUE);
		$config = $CI->config->item('erapor');
		return isset($config['grade_scale']) ? $config['grade_scale'] : array();
	}
}

if ( ! function_exists('score_to_predicate'))
{
	function score_to_predicate($score)
	{
		$scale = erapor_grade_scale();

		foreach ($scale as $predicate => $rule)
		{
			if ($score >= $rule['min'])
			{
				return $predicate;
			}
		}

		return 'D';
	}
}
