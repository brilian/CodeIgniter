<div class="card border-0 shadow-sm mb-4">
	<div class="card-body">
		<?php echo form_open('', array('method' => 'get', 'class' => 'row g-3 align-items-end')); ?>
			<div class="col-md-6">
				<label class="form-label required">Pilih Kelas</label>
				<select name="class_id" class="form-select" required onchange="this.form.submit()">
					<option value="">-- Pilih --</option>
					<?php foreach ($classes as $class): ?>
						<option value="<?php echo $class['id']; ?>" <?php echo $this->input->get('class_id') == $class['id'] ? 'selected' : ''; ?>>
							<?php echo $class['name']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-primary">Tampilkan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php if ($students): ?>
	<div class="card border-0 shadow-sm">
		<div class="table-responsive">
			<table class="table table-striped mb-0">
				<thead>
					<tr>
						<th>Nama</th>
						<th>NISN</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($students as $student): ?>
						<tr>
							<td><?php echo $student['full_name']; ?></td>
							<td><?php echo $student['nisn']; ?></td>
							<td class="text-end">
								<a href="<?php echo site_url('reports/show/'.$student['id']); ?>" class="btn btn-link btn-sm">Lihat</a>
								<a href="<?php echo site_url('reports/export/pdf/'.$student['id']); ?>" class="btn btn-link btn-sm">PDF</a>
								<a href="<?php echo site_url('reports/export/excel/'.$student['id']); ?>" class="btn btn-link btn-sm">Excel</a>
								<a href="<?php echo site_url('reports/export/template/'.$student['id']); ?>" class="btn btn-link btn-sm">Template XLSX</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php elseif ($this->input->get('class_id')): ?>
	<div class="alert alert-warning">Belum ada siswa.</div>
<?php endif; ?>
