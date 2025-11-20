<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label required">Judul Penilaian</label>
					<input type="text" name="title" class="form-control" value="<?php echo set_value('title'); ?>" required>
				</div>
				<div class="col-md-3">
					<label class="form-label required">Kelas</label>
					<select name="class_id" class="form-select" required>
						<option value="">Pilih Kelas</option>
						<?php foreach ($classes as $class): ?>
							<option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label required">Mapel</label>
					<select name="subject_id" class="form-select" required>
						<option value="">Pilih Mapel</option>
						<?php foreach ($subjects as $subject): ?>
							<option value="<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label required">Jenis Penilaian</label>
					<select name="type" class="form-select" required>
						<option value="pengetahuan">Pengetahuan</option>
						<option value="keterampilan">Keterampilan</option>
						<option value="sikap">Sikap</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Bobot (%)</label>
					<input type="number" name="weight" class="form-control" value="<?php echo set_value('weight', 20); ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label required">Tanggal Penilaian</label>
					<input type="date" name="due_date" class="form-control" value="<?php echo set_value('due_date', date('Y-m-d')); ?>" required>
				</div>
				<div class="col-12">
					<label class="form-label">Deskripsi</label>
					<textarea name="description" class="form-control" rows="3"><?php echo set_value('description'); ?></textarea>
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('grades'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
