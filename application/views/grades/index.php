<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Penilaian</h4>
	<a href="<?php echo site_url('grades/create'); ?>" class="btn btn-primary btn-sm">Buat Penilaian</a>
</div>

<?php echo form_open('', array('method' => 'get', 'class' => 'row g-3 align-items-end mb-3')); ?>
	<div class="col-md-4">
		<label class="form-label">Kelas</label>
		<select name="class_id" class="form-select" onchange="this.form.submit()">
			<option value="">Semua</option>
			<?php foreach ($classes as $class): ?>
				<option value="<?php echo $class['id']; ?>" <?php echo set_select('class_id', $class['id'], $this->input->get('class_id') == $class['id']); ?>>
					<?php echo $class['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-4">
		<label class="form-label">Mapel</label>
		<select name="subject_id" class="form-select" onchange="this.form.submit()">
			<option value="">Semua</option>
			<?php foreach ($subjects as $subject): ?>
				<option value="<?php echo $subject['id']; ?>" <?php echo set_select('subject_id', $subject['id'], $this->input->get('subject_id') == $subject['id']); ?>>
					<?php echo $subject['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
<?php echo form_close(); ?>

<div class="card border-0 shadow-sm">
	<div class="table-responsive">
		<table class="table table-striped mb-0">
			<thead>
				<tr>
					<th>Judul</th>
					<th>Kelas</th>
					<th>Mapel</th>
					<th>Jenis</th>
					<th>Tanggal</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($assessments as $assessment): ?>
					<tr>
						<td><?php echo $assessment['title']; ?></td>
						<td><?php echo $assessment['class_name']; ?></td>
						<td><?php echo $assessment['subject_name']; ?></td>
						<td><?php echo ucfirst($assessment['type']); ?></td>
						<td><?php echo $assessment['due_date']; ?></td>
						<td class="text-end">
							<a href="<?php echo site_url('grades/input/'.$assessment['id']); ?>" class="btn btn-link btn-sm">Input Nilai</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
