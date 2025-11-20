<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['erapor'] = array(
	'default_academic_year' => date('Y').'/'.(date('Y') + 1),
	'default_semester' => 1,
	'grade_scale' => array(
		'A' => array('min' => 90, 'description' => 'Sangat Baik'),
		'B' => array('min' => 80, 'description' => 'Baik'),
		'C' => array('min' => 70, 'description' => 'Cukup'),
		'D' => array('min' => 0, 'description' => 'Perlu Pembinaan')
	),
	'template_path' => APPPATH.'templates/rapor_template.xlsx',
	'template_upload_dir' => FCPATH.'storage/templates/',
	'report_output_dir' => FCPATH.'storage/reports/',
	'import_dir' => FCPATH.'storage/imports/',
);
