<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($page_title) ? $page_title.' | ' : ''; ?>e-Rapor</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
	<style>
		body { font-family: 'Inter', sans-serif; background-color: #f7f7f9; }
		.navbar-brand { font-weight: 700; letter-spacing: -0.5px; }
		.page-wrapper { padding-top: 4.5rem; }
		.flash { border-radius: .5rem; }
		footer { font-size: 0.85rem; color: #6c757d; }
		.required::after { content: '*'; color: #dc3545; margin-left: 0.25rem; }
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
		<div class="container-fluid">
			<a class="navbar-brand" href="<?php echo site_url('dashboard'); ?>">e-Rapor</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<?php if (has_role($current_user, array('admin', 'guru', 'wali'))): ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
					<?php endif; ?>
					<?php if (has_role($current_user, array('admin'))): ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('users'); ?>">Pengguna</a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('classes'); ?>">Kelas</a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('subjects'); ?>">Mapel</a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('templates'); ?>">Template</a></li>
					<?php endif; ?>
					<?php if (has_role($current_user, array('guru', 'wali'))): ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('grades'); ?>">Input Nilai</a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('rekap'); ?>">Rekap</a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('extracurricular'); ?>">Ekstrakurikuler</a></li>
					<?php endif; ?>
					<?php if (has_role($current_user, array('admin', 'guru', 'wali', 'siswa'))): ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports'); ?>">Rapor Saya</a></li>
					<?php endif; ?>
				</ul>
				<ul class="navbar-nav ms-auto">
					<?php if ($current_user): ?>
						<li class="nav-item">
							<span class="navbar-text me-3 text-white-50">
								<?php echo $current_user['name']; ?> (<?php echo role_label($current_user['role']); ?>)
							</span>
						</li>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Keluar</a></li>
					<?php else: ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/login'); ?>">Masuk</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container page-wrapper">
		<?php if (!empty($flash['success'])): ?>
			<div class="alert alert-success flash"><?php echo $flash['success']; ?></div>
		<?php endif; ?>
		<?php if (!empty($flash['error'])): ?>
			<div class="alert alert-danger flash"><?php echo $flash['error']; ?></div>
		<?php endif; ?>
		<?php if (!empty($flash['warning'])): ?>
			<div class="alert alert-warning flash"><?php echo $flash['warning']; ?></div>
		<?php endif; ?>

		<?php echo isset($content) ? $content : ''; ?>
	</div>

	<footer class="text-center py-4">
		<div>e-Rapor &copy; <?php echo date('Y'); ?>. Dibangun dengan CodeIgniter.</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
