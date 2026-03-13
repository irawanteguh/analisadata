<div class="row gy-5 g-xl-8 mb-xl-8">
    <div class="col-xl-12">
        <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/images/svg/misc/taieri.svg') ?>');">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div>
                        <h1>Data Kunjungan Pasien Rawat Jalan</h1>
                        <p class="mb-0">
							Data dan analisis kunjungan pasien rawat jalan.
						</p>
                    </div>
                </div>
                <div class="d-flex overflow-auto min-h-30px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
						<li class="nav-item">
							<a class="nav-link text-muted active" data-bs-toggle="tab" href="#booking">Booking</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#voiddocument">Void</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#faileddocument">Failed</a>
						</li> -->
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content mt-5">
	<div class="tab-pane fade active show" id="booking" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
			<?php      
				include_once(APPPATH."views/template/search.php");
			?>
			<div class="col-xl-12">
				<div class="card card-flush">
					<div class="card-header pt-5" id="">
						<h3 class="card-title align-items-start flex-column">
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
									<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tabledatadatabooking', 'Pasien Booking Rawat Jalan')">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-8">
						<div class="table-responsive">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tabledatadatabooking">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Mr</th>
										<th>Nama Pasien</th>
										<th>Provider</th>
										<th>Poliklinik</th>
										<th>Dokter</th>
										<th>Tanggal</th>
										<th class="text-end">Jam Mulai</th>
										<th class="text-end">Jam Selesai</th>
										<th>No Urut</th>
										<th>Kode Booking</th>
										<th class="pe-4 rounded-end text-end">No Handphone</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultdatadatabooking"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Top 10 Diagnosis OutPatient</span>
					<span class="text-muted mt-1 fw-bold fs-7">Top 10 Diagnosis Kunjungan Pasien Rawat Jalan</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatetop10diagnosis">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawtop10diagnosis">Raw Data</a>
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
					<div id="aggregatetop10diagnosis" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafiktopdiagnosarj"></div>
					</div>
					<div id="rawtop10diagnosis" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Diagnosa</th>
										<th class="pe-4 rounded-end text-end">Jumlah Kasus</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalandiagnosa"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Top 10 Diagnosis OutPatient Geriatri</span>
					<span class="text-muted mt-1 fw-bold fs-7">Top 10 Diagnosis Kunjungan Pasien Rawat Jalan Geriatri</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatetop10diagnosisgeriatri">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawtop10diagnosisgeriatri">Raw Data</a>
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
					<div id="aggregatetop10diagnosisgeriatri" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafiktopdiagnosarjgeriatri"></div>
					</div>
					<div id="rawtop10diagnosisgeriatri" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Diagnosa</th>
										<th class="pe-4 rounded-end text-end">Jumlah Kasus</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalandiagnosageriatri"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Pasien Rawat Jalan</span>
					<span class="text-muted mt-1 fw-bold fs-7">Kunjungan Pasien Rawat Jalan</span>
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
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tablerawdatarawatjalan">
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
										<th>No Transaksi</th>
										<th>Poli Tujuan</th>
										<th>Nama Dokter</th>
										<th>Tgl Masuk</th>
										<th>Tgl Keluar</th>
										<th>Umur Saat Pelayanan</th>
										<th>BB</th>
										<th>TB</th>
										<th>IMT</th>
										<th>Diagnosa</th>
										<th>Obat</th>
										<th class="pe-4 rounded-end text-end">Actions</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalan"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Poliklinik</span>
					<span class="text-muted mt-1 fw-bold fs-7">Distribusi Kunjungan Rawat Jalan per Poliklinik</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatepoli">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawpoli">Raw Data</a>
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
					<div id="aggregatepoli" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikrjpoli"></div>
					</div>
					<div id="rawpoli" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Poliklinik</th>
										<th class="pe-4 rounded-end text-end">Jumlah</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalanpoli"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Dokter Spesialis</span>
					<span class="text-muted mt-1 fw-bold fs-7">Distribusi Kunjungan Rawat Jalan per Dokter Spesialis</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatedokter">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawdokter">Raw Data</a>
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
					<div id="aggregatedokter" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikdokter"></div>
					</div>
					<div id="rawdokter" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Nama Dokter</th>
										<th class="pe-4 rounded-end text-end">Jumlah</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalandokter"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Provider</span>
					<span class="text-muted mt-1 fw-bold fs-7">Distribusi Kunjungan Rawat Jalan per Provider</span>
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
							<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tablerawdatarawatjalan', 'Laporan Kunjungan Rawat Jalan')">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="aggregateprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikprovider"></div>
					</div>
					<div id="rawprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="table-responsive mh-600px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">#</th>
										<th>Provider</th>
										<th class="pe-4 rounded-end text-end">Jumlah</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultrawdatarawatjalanprovider"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->