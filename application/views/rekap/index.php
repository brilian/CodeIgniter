<div class="card border-0 shadow-sm mb-4">
	<div class="card-body">
		<?php echo form_open('', array('method' => 'get', 'class' => 'row g-3 align-items-end')); ?>
			<div class="col-md-6">
				<label class="form-label">Pilih Kelas</label>
				<select name="class_id" class="form-select" required>
					<option value="">-- Pilih Kelas --</option>
					<?php foreach ($classes as $class): ?>
						<option value="<?php echo $class['id']; ?>" <?php echo $selected_class == $class['id'] ? 'selected' : ''; ?>>
							<?php echo $class['name']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-primary">Lihat Rekap</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php if ($selected_class): ?>
	<div class="row g-4">
		<div class="col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white">
					<h5 class="mb-0">Rata-rata per Mapel</h5>
				</div>
				<div class="table-responsive">
					<table class="table table-striped mb-0">
						<thead>
							<tr>
								<th>Mapel</th>
								<th class="text-end">Rata-rata</th>
								<th class="text-end">Entri</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($summary as $row): ?>
								<tr>
									<td><?php echo $row['subject_name']; ?></td>
									<td class="text-end"><?php echo number_format($row['avg_score'], 2); ?></td>
									<td class="text-end"><?php echo $row['total_entries']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white">
					<h5 class="mb-0">Daftar Siswa</h5>
				</div>
				<div class="list-group list-group-flush">
					<?php foreach ($students as $student): ?>
						<div class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<div class="fw-semibold"><?php echo $student['full_name']; ?></div>
								<small class="text-muted"><?php echo $student['nisn']; ?></small>
							</div>
							<a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('reports/show/'.$student['id']); ?>">Lihat Rapor</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
