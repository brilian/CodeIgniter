<div class="row justify-content-center">
	<div class="col-md-6 col-lg-4">
		<div class="card shadow-sm border-0">
			<div class="card-body">
				<h4 class="mb-3 text-center">Masuk e-Rapor</h4>
				<?php echo form_open('auth/login'); ?>
					<div class="mb-3">
						<label class="form-label required">Email</label>
						<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required>
						<small class="text-danger"><?php echo form_error('email'); ?></small>
					</div>
					<div class="mb-3">
						<label class="form-label required">Password</label>
						<input type="password" name="password" class="form-control" required>
						<small class="text-danger"><?php echo form_error('password'); ?></small>
					</div>
					<div class="d-grid">
						<button type="submit" class="btn btn-primary">Masuk</button>
					</div>
				<?php echo form_close(); ?>
				<div class="text-center mt-3">
					<a href="<?php echo site_url('password/forgot'); ?>">Lupa password?</a>
				</div>
			</div>
		</div>
	</div>
</div>
