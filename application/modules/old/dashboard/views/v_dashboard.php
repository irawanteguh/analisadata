<div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">File Analysis</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive mh-660px scroll-y me-n5 pe-5">
					<table class="table align-middle table-row-dashed fs-8 gy-2" id="tableanalisaklpcm">
						<thead class="align-middle">
							<tr class="fw-bolder text-muted bg-light">
                                <th class="ps-4 rounded-start">No MR</th>
                                <th>Nama</th>
								<th class="text-center">Tgl Masuk</th>
                                <th class="text-center">Tgl Keluar</th>
                                <th>Ruangan</th>
                                <th>Provider</th>
                                <th>Nama Dokter</th>
                                <th>Cara Pulang</th>
								<th>Tgl Dokumen Kembali</th>
								<th>General</th>
								<th>Informed</th>
								<th>Tindakan</th>
								<th class="pe-4 text-end rounded-end">Actions</th>
							</tr>
							<tr>
								<th><input id="filtermr" class="tagify form-control form-control-solid form-control-sm" placeholder="Medical Record"></th>
								<th><input id="filternama" class="tagify form-control form-control-solid form-control-sm" placeholder="Nama"></th>
								<th><input id="filtertglmasuk" class="tagify form-control form-control-solid form-control-sm" placeholder="Tgl Masuk"></th>
								<th><input id="filtertglkeluar" class="tagify form-control form-control-solid form-control-sm" placeholder="Tgl Keluar"></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<!-- <th><input id="filtergeneral" class="tagify form-control form-control-solid form-control-sm" placeholder="Status"></th>
								<th><input id="filterinformed" class="tagify form-control form-control-solid form-control-sm" placeholder="Status"></th> -->
								<th><input id="filteric" class="tagify form-control form-control-solid form-control-sm" placeholder="Status"></th>
							</tr>
						</thead>
						<tbody class="text-gray-600 fw-bold" id="resultdatakunjungan"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>