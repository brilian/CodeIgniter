<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-4">
					<label class="form-label required">NISN</label>
					<input type="text" name="nisn" class="form-control" value="<?php echo set_value('nisn', isset($student) ? $student['nisn'] : ''); ?>" required>
				</div>
				<div class="col-md-4">
					<label class="form-label">NIS</label>
					<input type="text" name="nis" class="form-control" value="<?php echo set_value('nis', isset($student) ? $student['nis'] : ''); ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label required">Kelas</label>
					<select name="class_id" class="form-select" required>
						<option value="">Pilih Kelas</option>
						<?php foreach ($classes as $class): ?>
							<option value="<?php echo $class['id']; ?>" <?php echo set_select('class_id', $class['id'], isset($student) && $student['class_id'] == $class['id']); ?>>
								<?php echo $class['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label required">Nama Lengkap</label>
					<input type="text" name="full_name" class="form-control" value="<?php echo set_value('full_name', isset($student) ? $student['full_name'] : ''); ?>" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Email Orang Tua/Siswa</label>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email', isset($student) ? $student['user_email'] : ''); ?>" <?php echo isset($student) ? 'readonly' : 'required'; ?>>
				</div>
				<div class="col-md-4">
					<label class="form-label">Jenis Kelamin</label>
					<select name="gender" class="form-select">
						<option value="L" <?php echo set_select('gender', 'L', isset($student) && $student['gender'] === 'L'); ?>>Laki-laki</option>
						<option value="P" <?php echo set_select('gender', 'P', isset($student) && $student['gender'] === 'P'); ?>>Perempuan</option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Tempat Lahir</label>
					<input type="text" name="birth_place" class="form-control" value="<?php echo set_value('birth_place', isset($student) ? $student['birth_place'] : ''); ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label">Tanggal Lahir</label>
					<input type="date" name="birth_date" class="form-control" value="<?php echo set_value('birth_date', isset($student) ? $student['birth_date'] : ''); ?>">
				</div>
				<div class="col-12">
					<label class="form-label">Alamat</label>
					<textarea name="address" class="form-control" rows="2"><?php echo set_value('address', isset($student) ? $student['address'] : ''); ?></textarea>
				</div>
				<div class="col-md-6">
					<label class="form-label">Nama Orang Tua</label>
					<input type="text" name="parent_name" class="form-control" value="<?php echo set_value('parent_name', isset($student) ? $student['parent_name'] : ''); ?>">
				</div>
				<?php if (!isset($student)): ?>
					<div class="col-md-6">
						<label class="form-label">Password Akun Siswa</label>
						<input type="text" name="password" class="form-control" placeholder="Default siswa123">
					</div>
				<?php endif; ?>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('students'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
