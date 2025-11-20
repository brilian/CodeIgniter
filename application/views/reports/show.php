<div class="card border-0 shadow-sm mb-4">
	<div class="card-body">
		<div class="d-flex justify-content-between">
			<div>
				<h4 class="mb-1"><?php echo $report['student']['full_name']; ?></h4>
				<p class="text-muted mb-0"><?php echo $report['student']['nisn']; ?> • <?php echo $report['student']['class_name']; ?> • Semester <?php echo $report['semester']; ?></p>
			</div>
			<div class="d-flex gap-2">
				<a href="<?php echo site_url('reports/export/pdf/'.$report['student']['id']); ?>" class="btn btn-outline-secondary btn-sm">Unduh PDF</a>
				<a href="<?php echo site_url('reports/export/excel/'.$report['student']['id']); ?>" class="btn btn-outline-secondary btn-sm">Unduh Excel</a>
			</div>
		</div>
	</div>
</div>

<div class="row g-4">
	<div class="col-md-7">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-white">
				<h5 class="mb-0">Nilai Akademik</h5>
			</div>
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead>
						<tr>
							<th>Mapel</th>
							<th class="text-end">Nilai</th>
							<th class="text-end">Predikat</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($report['grades'] as $grade): ?>
							<tr>
								<td><?php echo $grade['subject_name']; ?></td>
								<td class="text-end"><?php echo number_format($grade['score'], 2); ?></td>
								<td class="text-end"><?php echo score_to_predicate($grade['score']); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-white">
				<h5 class="mb-0">Ekstrakurikuler</h5>
			</div>
			<div class="list-group list-group-flush">
				<?php foreach ($report['extracurriculars'] as $activity): ?>
					<div class="list-group-item d-flex justify-content-between align-items-center">
						<div>
							<div class="fw-semibold"><?php echo $activity['extracurricular_name']; ?></div>
							<small class="text-muted"><?php echo $activity['note']; ?></small>
						</div>
						<span class="badge bg-primary rounded-pill"><?php echo $activity['predicate']; ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
