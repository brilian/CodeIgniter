<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Ekstrakurikuler</h4>
	<?php if (has_role($current_user, array('admin'))): ?>
		<a href="<?php echo site_url('extracurricular/create'); ?>" class="btn btn-primary btn-sm">Tambah Kegiatan</a>
	<?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
	<div class="table-responsive">
		<table class="table table-hover mb-0">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Pelatih</th>
					<th>Deskripsi</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($items as $item): ?>
					<tr>
						<td><?php echo $item['name']; ?></td>
						<td><?php echo $item['coach']; ?></td>
						<td><?php echo $item['description']; ?></td>
						<td class="text-end">
							<a href="<?php echo site_url('extracurricular/scores'); ?>" class="btn btn-link btn-sm">Nilai</a>
							<?php if (has_role($current_user, array('admin'))): ?>
								<a href="<?php echo site_url('extracurricular/edit/'.$item['id']); ?>" class="btn btn-link btn-sm">Edit</a>
								<a href="<?php echo site_url('extracurricular/delete/'.$item['id']); ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Hapus kegiatan ini?');">Hapus</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
