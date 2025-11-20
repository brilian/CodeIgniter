<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class Reports extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Report_model',
			'Student_model',
			'Classroom_model',
			'Report_template_model'
		));
		$this->config->load('erapor', TRUE);
	}

	public function index()
	{
		$this->authorize(array('admin', 'wali', 'guru', 'siswa'));

		if (has_role($this->current_user, array('siswa')))
		{
			$student = $this->Student_model->find_by_user($this->current_user['id']);
			return $this->show($student ? $student['id'] : NULL);
		}

		$data['page_title'] = 'Cetak Rapor';
		$data['classes'] = $this->Classroom_model->all();
		$data['students'] = array();
		$class_id = $this->input->get('class_id');
		if ($class_id)
		{
			$data['students'] = $this->Student_model->by_class($class_id);
		}
		$this->render('reports/index', $data);
	}

	public function show($student_id = NULL)
	{
		$this->authorize(array('admin', 'wali', 'guru', 'siswa'));

		if ( ! $student_id)
		{
			show_404();
		}

		$config = $this->config->item('erapor');
		$report = $this->Report_model->build_student_report($student_id, $config['default_academic_year'], $config['default_semester']);

		if ( ! $report)
		{
			show_404();
		}

		$data['page_title'] = 'Rapor '.$report['student']['full_name'];
		$data['report'] = $report;
		$this->render('reports/show', $data);
	}

	public function export_excel($student_id)
	{
		$this->authorize(array('admin', 'wali', 'guru', 'siswa'));
		$report = $this->_report_or_404($student_id);

		$spreadsheet = $this->_build_spreadsheet($report);
		$writer = new Xlsx($spreadsheet);
		$filename = 'rapor-'.$report['student']['nisn'].'.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit;
	}

	public function export_pdf($student_id)
	{
		$this->authorize(array('admin', 'wali', 'guru', 'siswa'));
		$report = $this->_report_or_404($student_id);

		$spreadsheet = $this->_build_spreadsheet($report);
		$pdf = new Mpdf($spreadsheet);
		$filename = 'rapor-'.$report['student']['nisn'].'.pdf';

		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$pdf->save('php://output');
		exit;
	}

	public function export_template($student_id)
	{
		$this->authorize(array('admin', 'wali', 'guru'));
		$report = $this->_report_or_404($student_id);
		$template = $this->Report_template_model->default_template();

		if ( ! $template || ! file_exists($template['file_path']))
		{
			return $this->export_excel($student_id);
		}

		$spreadsheet = IOFactory::load($template['file_path']);
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('B3', $report['student']['full_name']);
		$sheet->setCellValue('B4', $report['student']['nisn']);
		$sheet->setCellValue('B5', $report['student']['class_name']);

		$row = 10;
		foreach ($report['grades'] as $grade)
		{
			$sheet->setCellValue('A'.$row, $grade['subject_name']);
			$sheet->setCellValue('B'.$row, round($grade['score'], 2));
			$row++;
		}

		$writer = new Xlsx($spreadsheet);
		$filename = 'rapor-template-'.$report['student']['nisn'].'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit;
	}

	private function _report_or_404($student_id)
	{
		$config = $this->config->item('erapor');
		$report = $this->Report_model->build_student_report($student_id, $config['default_academic_year'], $config['default_semester']);
		if ( ! $report)
		{
			show_404();
		}

		if (has_role($this->current_user, array('siswa')) && $report['student']['user_id'] != $this->current_user['id'])
		{
			show_error('Anda tidak berhak mengakses rapor ini.', 403);
		}

		return $report;
	}

	private function _build_spreadsheet($report)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'RAPOR SISWA');
		$sheet->setCellValue('A3', 'Nama');
		$sheet->setCellValue('B3', $report['student']['full_name']);
		$sheet->setCellValue('A4', 'NISN');
		$sheet->setCellValue('B4', $report['student']['nisn']);
		$sheet->setCellValue('A5', 'Kelas');
		$sheet->setCellValue('B5', $report['student']['class_name']);
		$sheet->setCellValue('A6', 'Semester');
		$sheet->setCellValue('B6', $report['semester']);

		$sheet->setCellValue('A8', 'Mapel');
		$sheet->setCellValue('B8', 'Nilai');
		$row = 9;
		foreach ($report['grades'] as $grade)
		{
			$sheet->setCellValue('A'.$row, $grade['subject_name']);
			$sheet->setCellValue('B'.$row, round($grade['score'], 2));
			$row++;
		}

		$row += 2;
		$sheet->setCellValue('A'.$row, 'Ekstrakurikuler');
		$sheet->setCellValue('B'.$row, 'Predikat');
		$row++;
		foreach ($report['extracurriculars'] as $activity)
		{
			$sheet->setCellValue('A'.$row, $activity['extracurricular_name']);
			$sheet->setCellValue('B'.$row, $activity['predicate']);
			$row++;
		}

		return $spreadsheet;
	}
}
