<?php
$gradeMap = array();
foreach ($grades as $grade) {
	$gradeMap[$grade['student_id']] = $grade;
}
?>

<div class="card border-0 shadow-sm">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<div>
				<h5 class="mb-1"><?php echo $assessment['title']; ?></h5>
				<small class="text-muted"><?php echo $assessment['class_name']; ?> • <?php echo $assessment['subject_name']; ?> • <?php echo ucfirst($assessment['type']); ?></small>
			</div>
			<div class="d-flex gap-2">
				<?php if (has_role($current_user, array('admin', 'wali'))): ?>
					<a href="<?php echo site_url('grades/lock/'.$assessment['id']); ?>" class="btn btn-outline-secondary btn-sm">Kunci</a>
					<a href="<?php echo site_url('grades/unlock/'.$assessment['id']); ?>" class="btn btn-outline-secondary btn-sm">Buka Kunci</a>
				<?php endif; ?>
				<a href="<?php echo site_url('grades'); ?>" class="btn btn-light btn-sm">Kembali</a>
			</div>
		</div>

		<?php echo form_open(); ?>
			<div class="table-responsive">
				<table class="table table-bordered align-middle">
					<thead class="table-light">
						<tr>
							<th>Nama</th>
							<th width="120">Nilai</th>
							<th width="120">Predikat</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($students as $student): ?>
							<?php
								$existing = isset($gradeMap[$student['id']]) ? $gradeMap[$student['id']] : array();
							?>
							<tr>
								<td><?php echo $student['full_name']; ?></td>
								<td>
									<input type="number" class="form-control" name="scores[<?php echo $student['id']; ?>][score]" step="0.01" min="0" max="100" value="<?php echo isset($existing['score']) ? $existing['score'] : ''; ?>">
								</td>
								<td>
									<input type="text" class="form-control" name="scores[<?php echo $student['id']; ?>][predicate]" value="<?php echo isset($existing['predicate']) ? $existing['predicate'] : ''; ?>" maxlength="1">
								</td>
								<td>
									<input type="text" class="form-control" name="scores[<?php echo $student['id']; ?>][note]" value="<?php echo isset($existing['note']) ? $existing['note'] : ''; ?>">
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="text-end">
				<button type="submit" class="btn btn-primary">Simpan Nilai</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
