<div class="row">
	<div class="col-xl-3 col-md-6 mb-5">
		<a href="#" class="card bg-info border-0 shadow-sm h-100 text-decoration-none">
			<div class="card-body p-5">
				<div class="d-flex align-items-center justify-content-between mb-4">
					<i class="bi bi-person-check-fill text-white fs-2x"></i>
					<i class="bi bi-box-arrow-right text-white opacity-50 fs-4"></i>
				</div>
				<div class="text-white fw-bold fs-4 mb-1">Pasien Pulang</div>
				<div class="text-white opacity-75 fw-semibold fs-7">Total Pasien Pulang Rawat Inap</div>
				<div class="text-white fw-bolder fs-2 mt-2" id="totalpasienpulang">0 Px</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-md-6 mb-5">
		<a href="#" class="card bg-success border-0 shadow-sm h-100 text-decoration-none">
			<div class="card-body p-5">
				<div class="d-flex align-items-center justify-content-between mb-4">
					<i class="bi bi-file-earmark-medical-fill text-white fs-2x"></i>
					<i class="bi bi-check-circle-fill text-white opacity-50 fs-4"></i>
				</div>
				<div class="text-white fw-bold fs-4 mb-1">Resume Selesai</div>
				<div class="text-white opacity-75 fw-semibold fs-7">Total Resume Yang Telah Dibuat</div>
				<div class="text-white fw-bolder fs-2 mt-2" id="totalresume">0 Px</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-md-6 mb-5">
		<a href="#" class="card bg-primary border-0 shadow-sm h-100 text-decoration-none">
			<div class="card-body p-5">
				<div class="d-flex align-items-center justify-content-between mb-4">
					<i class="bi bi-clock-history text-white fs-2x"></i>
					<span class="badge bg-white text-primary rounded-pill px-3 py-2">≤ 48 Jam</span>
				</div>
				<div class="text-white fw-bold fs-4 mb-1">Pending Resume</div>
				<div class="text-white opacity-75 fw-semibold fs-7">Resume Medis Belum Selesai</div>
				<div class="text-white fw-bolder fs-2 mt-2" id="pendingresumekurang">0 Px</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-md-6 mb-5">
		<a href="#" class="card bg-danger border-0 shadow-sm h-100 text-decoration-none">
			<div class="card-body p-5">
				<div class="d-flex align-items-center justify-content-between mb-4">
					<i class="bi bi-exclamation-octagon-fill text-white fs-2x"></i>
					<span class="badge bg-white text-danger rounded-pill px-3 py-2">&gt; 48 Jam</span>
				</div>
				<div class="text-white fw-bold fs-4 mb-1">Pending Resume</div>
				<div class="text-white opacity-75 fw-semibold fs-7">Resume Medis Melebihi Batas</div>
				<div class="text-white fw-bolder fs-2 mt-2" id="pendingresumelebih">0 Px</div>
			</div>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 mb-5">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Laporan Resume Medis Rawat Inap</span>
					<span class="text-muted mt-1 fw-bold fs-7">Monitoring kelengkapan dan ketepatan waktu penyelesaian resume medis rawat inap untuk mendukung mutu rekam medis dan kepatuhan standar pelayanan.</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabsummary">Summary</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabindikatormutu">Indikator Mutu</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabraw">Raw Resume Medis</a>
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
							<a href="#" class="menu-link px-3" id="btndownloaddatadetailri_table">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="tabsummary" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="row">
							<div class="col-xl-9">
								<div id="grafikresumemedis"></div>
							</div>
							<div class="col-xl-3">
								<div id="grafikresumemedisglobal"></div>
							</div>
						</div>
					</div>
					<div id="tabindikatormutu" class="card-body p-0 tab-pane fade" role="tabpanel">
						
					</div>
					<div id="tabraw" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="d-flex align-items-center position-relative my-1 mb-4">
							<span class="svg-icon svg-icon-1 position-absolute ms-6">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
									<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
								</svg>
							</span>
							<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Data" id="searchtable">
						</div>
						<div class="table-responsive">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="datapendingresume_table">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>No MR</th>
										<th>Nama Pasien</th>
										<th>Sex</th>
										<th>Ruangan</th>
										<th>Kelas</th>
										<th>Nama Dokter</th>
										<th>Tgl Masuk</th>
										<th>Tgl Keluar</th>
										<th>Provider</th>
										<th>Cara Pulang</th>
										<th>Status</th>
										<th>Tanggal Resume</th>
										<th class="pe-4 text-end rounded-end">Actions</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultdatapendingresume"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4 mb-5">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Pending Resume Dokter DPJP</span>
					<span class="text-muted mt-1 fw-semibold fs-7">Daftar resume medis yang belum diselesaikan oleh dokter DPJP</span>
				</h3>
				<div class="card-toolbar m-0">
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
							<a href="#" class="menu-link px-3" id="btndownloaddatacarakeluar_table">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed gy-2 fs-8" id="datapendingresumedokter_table">
						<thead class="align-middle">
							<tr class="fw-bolder text-muted bg-light">
								<th class="ps-4 rounded-start">#</th>
								<th>Nama Dokter</th>
								<th>Jumlah</th>
								<th class="pe-4 text-end rounded-end">Actions</th>
							</tr>
						</thead>
						<tbody class="fw-bold text-gray-600" id="resultdatapendingresumedokter"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
