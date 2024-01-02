<div class="content-wrapper">

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h4 class="m-0"><?= $description ?></h4>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<?= $breadcrumbs ?>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="content-loader-wrapper" style="display: none;">
							<span class="loader"><span class="loader-inner"></span></span>
						</div>
						<div class="card-body">
							<a href="#" id="tambah_template_btn" class="btn btn-success mb-3"><i class="fa fa-plus"></i> Tambah Template</a>
							<table id="table_shift_template" class="table table-bordered table-striped dataTable no-footer dtr-inline">
								<thead>
									<tr>
										<th>No.</th>
										<th>Nama Template</th>
										<th>Akronim</th>
										<th>Jam Masuk</th>
										<th>Jam Pulang</th>
										<th>Jumlah Jam</th>
										<th>Deskripsi</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($shift_templates as $tp) {
										echo '<tr>
											<td>' . $tp[0] . '</td>
											<td>' . $tp[1] . '</td>
											<td>' . $tp[2] . '</td>
											<td>' . $tp[3] . '</td>
											<td>' . $tp[4] . '</td>
											<td>' . $tp[5] . '</td>
											<td>' . $tp[6] . '</td>
											<td>' . $tp[7] . '</td>
										</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="tambah_template_mdl" tabindex="-1" role="dialog" aria-labelledby="tambahTemplateMdl" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="position: relative">
			<div class="content-loader-wrapper modal-loader" style="border-radius: .200rem !important; display: none">
				<span class="loader"><span class="loader-inner"></span></span>
			</div>
			<div class="modal-header">
				<h5 class="modal-title">Template Shift Baru</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form>
				<div class="modal-body">
					<div class="form-group">
						<label for="nama_template_txt" class="col-form-label">Nama Template</label>
						<input type="text" class="form-control" name="nama_template" id="nama_template_txt">
					</div>

					<div class="form-group">
						<label for="akronim_txt" class="col-form-label">Akronim</label>
						<input type="text" class="form-control" name="akronim" id="akronim_txt">
					</div>

					<div class="form-group">
						<label for="jam_masuk_tm" class="col-form-label">Jam Masuk</label>
						<input type="time" class="form-control" name="jam_masuk" id="jam_masuk_tm">
					</div>

					<div class="form-group">
						<label for="jam_pulang_tm" class="col-form-label">Jam Pulang</label>
						<input type="time" class="form-control" name="jam_pulang" id="jam_pulang_tm">
					</div>

					<div class="form-group">
						<label for="jumlah_jam_num" class="col-form-label">Jumlah Jam</label>
						<input type="number" min="0" step=".1" class="form-control" name="jumlah_jam" id="jumlah_jam_num">
					</div>

					<div class="form-group">
						<label for="deskripsi_txt" class="col-form-label">Deskripsi</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi_txt"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
					<button type="submit" class="btn btn-primary">Simpan Template</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="edit_template_mdl" tabindex="-1" role="dialog" aria-labelledby="editTemplateMdl" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="position: relative">
			<div class="content-loader-wrapper modal-loader" style="border-radius: .200rem !important; display: none">
				<span class="loader"><span class="loader-inner"></span></span>
			</div>
			<div class="modal-header">
				<h5 class="modal-title">Edit Template</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form>
				<div class="modal-body">
					<div class="form-group">
						<label for="nama_template_txt" class="col-form-label">Nama Template</label>
						<input type="text" class="form-control" name="nama_template" id="nama_template_txt">
						<input type="hidden" value="" name="id_template">
					</div>

					<div class="form-group">
						<label for="akronim_txt" class="col-form-label">Akronim</label>
						<input type="text" class="form-control" name="akronim" id="akronim_txt">
					</div>

					<div class="form-group">
						<label for="jam_masuk_tm" class="col-form-label">Jam Masuk</label>
						<input type="time" class="form-control" name="jam_masuk" id="jam_masuk_tm">
					</div>

					<div class="form-group">
						<label for="jam_pulang_tm" class="col-form-label">Jam Pulang</label>
						<input type="time" class="form-control" name="jam_pulang" id="jam_pulang_tm">
					</div>

					<div class="form-group">
						<label for="jumlah_jam_num" class="col-form-label">Jumlah Jam</label>
						<input type="number" class="form-control" name="jumlah_jam" id="jumlah_jam_num">
					</div>

					<div class="form-group">
						<label for="deskripsi_txt" class="col-form-label">Deskripsi</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi_txt"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
					<button type="submit" class="btn btn-primary">Simpan Template</button>
				</div>
			</form>
		</div>
	</div>
</div>
