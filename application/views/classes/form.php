<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label required">Nama Kelas</label>
					<input type="text" class="form-control" name="name" value="<?php echo set_value('name', isset($class) ? $class['name'] : ''); ?>" required>
				</div>
				<div class="col-md-3">
					<label class="form-label required">Tingkat</label>
					<input type="text" class="form-control" name="level" value="<?php echo set_value('level', isset($class) ? $class['level'] : ''); ?>" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Jurusan</label>
					<input type="text" class="form-control" name="major" value="<?php echo set_value('major', isset($class) ? $class['major'] : ''); ?>">
				</div>
				<div class="col-md-4">
					<label class="form-label required">Tahun Ajaran</label>
					<input type="text" class="form-control" name="academic_year" value="<?php echo set_value('academic_year', isset($class) ? $class['academic_year'] : ''); ?>" required>
				</div>
				<div class="col-md-2">
					<label class="form-label required">Semester</label>
					<input type="number" class="form-control" name="semester" value="<?php echo set_value('semester', isset($class) ? $class['semester'] : '1'); ?>" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Wali Kelas</label>
					<select name="wali_user_id" class="form-select">
						<option value="">-- Pilih Wali --</option>
						<?php foreach ($wali_candidates as $wali): ?>
							<option value="<?php echo $wali['id']; ?>" <?php echo set_select('wali_user_id', $wali['id'], isset($class) && $class['wali_user_id'] == $wali['id']); ?>>
								<?php echo $wali['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-12">
					<label class="form-label">Catatan</label>
					<textarea name="description" class="form-control" rows="3"><?php echo set_value('description', isset($class) ? $class['description'] : ''); ?></textarea>
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('classes'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
