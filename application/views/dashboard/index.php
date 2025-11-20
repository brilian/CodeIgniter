<div class="row g-3 mb-4">
	<?php foreach ($stats as $key => $value): ?>
		<div class="col-6 col-md-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body">
					<p class="text-muted text-uppercase small mb-1"><?php echo ucfirst($key); ?></p>
					<h3 class="fw-semibold"><?php echo $value; ?></h3>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php if (!empty($upcoming_assessments)): ?>
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-header bg-white">
			<h5 class="mb-0">Penilaian Mendatang</h5>
		</div>
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead>
					<tr>
						<th>Judul</th>
						<th>Kelas</th>
						<th>Mapel</th>
						<th>Jenis</th>
						<th>Jatuh Tempo</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($upcoming_assessments as $assessment): ?>
						<tr>
							<td><?php echo $assessment['title']; ?></td>
							<td><?php echo $assessment['class_name']; ?></td>
							<td><?php echo $assessment['subject_name']; ?></td>
							<td><?php echo ucfirst($assessment['type']); ?></td>
							<td><?php echo $assessment['due_date']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>

<?php if (!empty($classes)): ?>
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-header bg-white">
			<h5 class="mb-0">Kelas Binaan</h5>
		</div>
		<div class="list-group list-group-flush">
			<?php foreach ($classes as $class): ?>
				<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?php echo site_url('rekap?class_id='.$class['id']); ?>">
					<div>
						<div class="fw-semibold"><?php echo $class['name']; ?></div>
						<small class="text-muted"><?php echo $class['academic_year'].' / Semester '.$class['semester']; ?></small>
					</div>
					<span class="badge bg-primary rounded-pill"><?php echo $class['level']; ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php if (!empty($student)): ?>
	<div class="row g-4">
		<div class="col-md-6">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<h5 class="mb-3">Profil Saya</h5>
					<p class="mb-1"><span class="text-muted">Nama</span><br><?php echo $student['full_name']; ?></p>
					<p class="mb-1"><span class="text-muted">NISN</span><br><?php echo $student['nisn']; ?></p>
					<p class="mb-1"><span class="text-muted">Kelas</span><br><?php echo $student['class_name']; ?></p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<h5 class="mb-3">Rata-rata Nilai</h5>
					<?php if (!empty($grade_summary)): ?>
						<ul class="list-group list-group-flush">
							<?php foreach ($grade_summary as $row): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span><?php echo $row['subject_name']; ?></span>
									<span class="fw-semibold"><?php echo round($row['avg_score'], 2); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						<p class="text-muted mb-0">Belum ada nilai.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
