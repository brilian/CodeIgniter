<div class="card border-0 shadow-sm mb-4">
	<div class="card-body">
		<?php echo form_open('', array('method' => 'get', 'class' => 'row g-3 align-items-end')); ?>
			<div class="col-md-6">
				<label class="form-label required">Pilih Kelas</label>
				<select name="class_id" class="form-select" required onchange="this.form.submit()">
					<option value="">-- Pilih --</option>
					<?php foreach ($classes as $class): ?>
						<option value="<?php echo $class['id']; ?>" <?php echo $selected_class == $class['id'] ? 'selected' : ''; ?>>
							<?php echo $class['name']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-primary">Muat</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php if ($selected_class && $students): ?>
	<div class="card border-0 shadow-sm">
		<div class="card-body">
			<?php echo form_open(); ?>
				<input type="hidden" name="class_id" value="<?php echo $selected_class; ?>">
				<div class="table-responsive">
					<table class="table table-bordered align-middle">
						<thead class="table-light">
							<tr>
								<th>Siswa</th>
								<?php foreach ($activities as $activity): ?>
									<th><?php echo $activity['name']; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($students as $student): ?>
								<tr>
									<td><?php echo $student['full_name']; ?></td>
									<?php foreach ($activities as $activity): ?>
										<?php
											$existing = isset($score_map[$student['id']][$activity['id']]) ? $score_map[$student['id']][$activity['id']] : array();
										?>
										<td>
											<input type="text" class="form-control mb-2" name="scores[<?php echo $student['id']; ?>][<?php echo $activity['id']; ?>][predicate]" placeholder="Predikat (A-D)" value="<?php echo isset($existing['predicate']) ? $existing['predicate'] : ''; ?>">
											<input type="text" class="form-control" name="scores[<?php echo $student['id']; ?>][<?php echo $activity['id']; ?>][note]" placeholder="Catatan" value="<?php echo isset($existing['note']) ? $existing['note'] : ''; ?>">
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="text-end">
					<button type="submit" class="btn btn-primary">Simpan Nilai Ekstrakurikuler</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
<?php elseif ($selected_class): ?>
	<div class="alert alert-warning">Belum ada siswa di kelas ini.</div>
<?php endif; ?>
