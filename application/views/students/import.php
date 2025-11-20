<div class="card border-0 shadow-sm">
	<div class="card-body">
		<h5 class="mb-3">Import Siswa dari Excel</h5>
		<p class="text-muted">Gunakan template yang disediakan agar format sesuai. Kolom wajib: NISN, Nama, Email, Password, Jenis Kelamin, Alamat.</p>
		<?php echo form_open_multipart('students/import'); ?>
			<div class="mb-3">
				<label class="form-label required">Kelas Tujuan</label>
				<select name="class_id" class="form-select" required>
					<option value="">Pilih Kelas</option>
					<?php foreach ($classes as $class): ?>
						<option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="mb-3">
				<label class="form-label required">File Excel</label>
				<input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
			</div>
			<div class="d-flex justify-content-between">
				<a href="<?php echo site_url('students'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Upload & Import</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
