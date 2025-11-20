<div class="row g-4">
	<div class="col-md-7">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-white">
				<h5 class="mb-0">Template Tersedia</h5>
			</div>
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead>
						<tr>
							<th>Nama</th>
							<th>Default</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($templates as $template): ?>
							<tr>
								<td><?php echo $template['name']; ?></td>
								<td>
									<?php if ($template['is_default']): ?>
										<span class="badge bg-success">Ya</span>
									<?php else: ?>
										<a href="<?php echo site_url('templates/set_default/'.$template['id']); ?>" class="btn btn-link btn-sm">Jadikan Default</a>
									<?php endif; ?>
								</td>
								<td class="text-end">
									<a href="<?php echo site_url('templates/delete/'.$template['id']); ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Hapus template ini?');">Hapus</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card border-0 shadow-sm">
			<div class="card-body">
				<h5 class="mb-3">Upload Template Baru</h5>
				<?php echo form_open_multipart('templates/upload'); ?>
					<div class="mb-3">
						<label class="form-label">Nama Template</label>
						<input type="text" name="name" class="form-control">
					</div>
					<div class="mb-3">
						<label class="form-label required">File Excel</label>
						<input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Catatan</label>
						<textarea name="description" class="form-control" rows="2"></textarea>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" name="is_default" id="is_default">
						<label class="form-check-label" for="is_default">
							Jadikan sebagai template utama
						</label>
					</div>
					<button type="submit" class="btn btn-primary w-100">Upload</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
