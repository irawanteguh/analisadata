<div class="col-xl-12">
	<div class="card card-flush">
		<div class="card-header pt-5" id="">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bolder fs-3 mb-1">Re-Admisi Rawat Inap</span>
				<span class="text-muted mt-1 fw-bold fs-7">Pemantauan pasien yang kembali dirawat inap dalam periode tertentu setelah sebelumnya menjalani perawatan</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregateprovider">Aggregate Data</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawprovider">Raw Data</a>
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
						<a href="#" class="menu-link px-3" id="btnDownloadExcelReAdmisi">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div class="tab-content">
				<div id="aggregateprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="card-rounded-bottom" id="grafikreadmisiaggregate"></div>
				</div>
				<div id="rawprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
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
						<table class="table align-middle table-row-dashed gy-2 fs-8" id="datapasienreadmisi_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-white bg-dark">
									<th class="ps-4 rounded-start" rowspan="2">#</th>
									<th rowspan="2">No MR</th>
									<th rowspan="2">Nama</th>
									<th class="text-center bg-primary" colspan="3">Kunjungan Saat Ini</th>
									<th class="text-center bg-info" colspan="3">Kunjungan Saat Lalu</th>
									<th rowspan="2">Waktu</th>
									<th class="pe-4 rounded-end text-end" rowspan="2">Actions</th>
								</tr>
								<tr class="fw-bolder text-white bg-light">
									<th class="text-center bg-primary">Tgl Masuk</th>
									<th class="text-center bg-primary">Tgl Keluar</th>
									<th class="text-center bg-primary">DPJP</th>
									<th class="text-center bg-info">Tgl Masuk</th>
									<th class="text-center bg-info">Tgl Keluar</th>
									<th class="text-center bg-info">DPJP</th>
								</tr>
							</thead>
							<tbody class="text-gray-600 fw-bold" id="resultdatapasienreadmisi"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>