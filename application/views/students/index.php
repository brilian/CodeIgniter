<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Siswa</h4>
	<div class="d-flex gap-2">
		<a href="<?php echo site_url('students/import'); ?>" class="btn btn-outline-secondary btn-sm">Import</a>
		<a href="<?php echo site_url('students/export/template'); ?>" class="btn btn-outline-secondary btn-sm">Template Excel</a>
		<a href="<?php echo site_url('students/create'); ?>" class="btn btn-primary btn-sm">Tambah Siswa</a>
	</div>
</div>

<?php echo form_open('', array('method' => 'get', 'class' => 'row g-3 align-items-end mb-3')); ?>
	<div class="col-md-4">
		<label class="form-label">Filter Kelas</label>
		<select name="class_id" class="form-select" onchange="this.form.submit()">
			<option value="">Semua Kelas</option>
			<?php foreach ($classes as $class): ?>
				<option value="<?php echo $class['id']; ?>" <?php echo $selected_class == $class['id'] ? 'selected' : ''; ?>>
					<?php echo $class['name']; ?>
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
					<th>NISN</th>
					<th>Nama</th>
					<th>Kelas</th>
					<th>Jenis Kelamin</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($students as $student): ?>
					<tr>
						<td><?php echo $student['nisn']; ?></td>
						<td><?php echo $student['full_name']; ?></td>
						<td><?php echo $student['class_name']; ?></td>
						<td><?php echo $student['gender']; ?></td>
						<td class="text-end">
							<a href="<?php echo site_url('students/edit/'.$student['id']); ?>" class="btn btn-link btn-sm">Edit</a>
							<?php if (has_role($current_user, array('admin'))): ?>
								<a href="<?php echo site_url('students/delete/'.$student['id']); ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Hapus siswa ini?');">Hapus</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
