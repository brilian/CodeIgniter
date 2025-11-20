<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-4">
					<label class="form-label required">Kode</label>
					<input type="text" class="form-control" name="code" value="<?php echo set_value('code', isset($subject) ? $subject['code'] : ''); ?>" required>
				</div>
				<div class="col-md-8">
					<label class="form-label required">Nama Mapel</label>
					<input type="text" class="form-control" name="name" value="<?php echo set_value('name', isset($subject) ? $subject['name'] : ''); ?>" required>
				</div>
				<div class="col-md-3">
					<label class="form-label required">KKM</label>
					<input type="number" class="form-control" name="kkm" value="<?php echo set_value('kkm', isset($subject) ? $subject['kkm'] : '75'); ?>" required>
				</div>
				<div class="col-12">
					<label class="form-label">Deskripsi</label>
					<textarea class="form-control" rows="3" name="description"><?php echo set_value('description', isset($subject) ? $subject['description'] : ''); ?></textarea>
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('subjects'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
