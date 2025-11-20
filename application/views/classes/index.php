<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Daftar Kelas</h4>
	<a href="<?php echo site_url('classes/create'); ?>" class="btn btn-primary btn-sm">Tambah Kelas</a>
</div>

<div class="card border-0 shadow-sm">
	<div class="table-responsive">
		<table class="table table-hover mb-0">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Tingkat</th>
					<th>Wali Kelas</th>
					<th>Tahun Ajaran</th>
					<th>Semester</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($classes as $class): ?>
					<tr>
						<td><?php echo $class['name']; ?></td>
						<td><?php echo $class['level']; ?></td>
						<td><?php echo $class['wali_name'] ?: '-'; ?></td>
						<td><?php echo $class['academic_year']; ?></td>
						<td><?php echo $class['semester']; ?></td>
						<td class="text-end">
							<a href="<?php echo site_url('classes/edit/'.$class['id']); ?>" class="btn btn-link btn-sm">Edit</a>
							<a href="<?php echo site_url('classes/delete/'.$class['id']); ?>" class="btn btn-link text-danger btn-sm" onclick="return confirm('Hapus kelas ini?');">Hapus</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
