<div class="card border-0 shadow-sm">
	<div class="card-body">
		<?php echo form_open(); ?>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label required">Nama</label>
					<input type="text" name="name" class="form-control" value="<?php echo set_value('name', isset($user) ? $user['name'] : ''); ?>" required>
					<small class="text-danger"><?php echo form_error('name'); ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label required">Email</label>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email', isset($user) ? $user['email'] : ''); ?>" required>
					<small class="text-danger"><?php echo form_error('email'); ?></small>
				</div>
				<div class="col-md-6">
					<label class="form-label required">Role</label>
					<select name="role" class="form-select" required>
						<?php foreach (role_options() as $key => $label): ?>
							<option value="<?php echo $key; ?>" <?php echo set_select('role', $key, isset($user) && $user['role'] === $key); ?>><?php echo $label; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label required">Status</label>
					<select name="status" class="form-select">
						<option value="active" <?php echo set_select('status', 'active', isset($user) ? $user['status'] === 'active' : TRUE); ?>>Aktif</option>
						<option value="inactive" <?php echo set_select('status', 'inactive', isset($user) && $user['status'] === 'inactive'); ?>>Nonaktif</option>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label <?php echo isset($user) ? '' : 'required'; ?>">Password</label>
					<input type="password" name="password" class="form-control" <?php echo isset($user) ? '' : 'required'; ?>>
					<?php if (isset($user)): ?>
						<small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
					<?php endif; ?>
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center mt-4">
				<a href="<?php echo site_url('users'); ?>" class="btn btn-light">Batal</a>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
