<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Mata Pelajaran</h4>
	<a href="<?php echo site_url('subjects/create'); ?>" class="btn btn-primary btn-sm">Tambah Mapel</a>
</div>

<div class="card border-0 shadow-sm">
	<div class="table-responsive">
		<table class="table table-hover mb-0">
			<thead>
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>KKM</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($subjects as $subject): ?>
					<tr>
						<td><?php echo $subject['code']; ?></td>
						<td><?php echo $subject['name']; ?></td>
						<td><?php echo $subject['kkm']; ?></td>
						<td class="text-end">
							<a href="<?php echo site_url('subjects/edit/'.$subject['id']); ?>" class="btn btn-link btn-sm">Edit</a>
							<a href="<?php echo site_url('subjects/delete/'.$subject['id']); ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Hapus mapel ini?');">Hapus</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
