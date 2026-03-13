<div class="row gy-5 g-xl-8 mb-xl-8">
	<?php      
		include_once(APPPATH."views/template/search.php");
	?>
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Pasien Rawat Inap</span>
					<span class="text-muted mt-1 fw-bold fs-7">Kunjungan Pasien Rawat Inap</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#rawdatarawatjalan">Raw Data</a>
						</li>
					</ul>
					<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
						<span class="svg-icon svg-icon-3 svg-icon-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
									<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
									<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
									<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
								</g>
							</svg>
						</span>
					</button>
					<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
						<div class="menu-item px-3">
							<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">More Actions</div>
						</div>
						<div class="menu-item px-3">
							<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tablerawdatarawatjalan', 'Laporan Kunjungan Rawat Jalan')">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="rawdatarawatjalan" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5" style="overflow-x:auto; white-space:nowrap;">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tablerawdatarawatinap">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Mr</th>
										<th>Nama Pasien</th>
										<th>Sex</th>
										<th>Geriatri</th>
										<th>Tempat Lhr</th>
										<th>Tanggal Lhr</th>
										<th>Umur Saat Ini</th>
										<th>Nama Dokter</th>
										<th>Tgl Masuk</th>
										<th>Tgl Keluar</th>
										<th>Umur Saat Pelayanan</th>
										<th>Diagnosa</th>
										<th class="pe-4 rounded-end text-end">Actions</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatinap"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>