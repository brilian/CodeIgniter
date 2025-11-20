<div class="d-flex justify-content-between align-items-center mb-3">
	<h4 class="mb-0">Pengguna</h4>
	<a href="<?php echo site_url('users/create'); ?>" class="btn btn-primary btn-sm">Tambah Pengguna</a>
</div>

<div class="card border-0 shadow-sm">
	<div class="table-responsive">
		<table class="table table-striped mb-0">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Email</th>
					<th>Role</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo $user['name']; ?></td>
						<td><?php echo $user['email']; ?></td>
						<td><?php echo role_label($user['role']); ?></td>
						<td>
							<span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
								<?php echo ucfirst($user['status']); ?>
							</span>
						</td>
						<td class="text-end">
							<a class="btn btn-link btn-sm" href="<?php echo site_url('users/edit/'.$user['id']); ?>">Edit</a>
							<a class="btn btn-link btn-sm" href="<?php echo site_url('users/reset-password/'.$user['id']); ?>">Reset Password</a>
							<a class="btn btn-link text-danger btn-sm" href="<?php echo site_url('users/delete/'.$user['id']); ?>" onclick="return confirm('Hapus pengguna ini?');">Hapus</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
