<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label required">Nama Kegiatan</label>
					<input type="text" name="name" class="form-control" value="<?php echo set_value('name', isset($item) ? $item['name'] : ''); ?>" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Pembina/Pelatih</label>
					<input type="text" name="coach" class="form-control" value="<?php echo set_value('coach', isset($item) ? $item['coach'] : ''); ?>">
				</div>
				<div class="col-12">
					<label class="form-label">Deskripsi</label>
					<textarea name="description" class="form-control" rows="3"><?php echo set_value('description', isset($item) ? $item['description'] : ''); ?></textarea>
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('extracurricular'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
