<div class="col-xl-12">
    <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/media/svg/misc/taieri.svg') ?>');">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                <div>
                    <h1>Laporan KPI</h1>
                    <p class="mb-0">
                        Monitoring kinerja pelayanan pasien berdasarkan waktu proses layanan dan tingkat kepatuhan terhadap standar yang ditetapkan guna mendukung peningkatan mutu dan kualitas pelayanan kesehatan.
                    </p>
                </div>
            </div>
            <div class="d-flex overflow-auto min-h-30px">
                <ul class="nav nav-stretch nav-line-tabs border-transparent fs-6 fw-bold flex-nowrap">
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab1">Pelayanan RJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab2">Tunggu RJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab3">Batal OK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab4">Layanan IGD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab5">Masuk RI</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab6">Jam Pulang Pasien Rawat Inap</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="tab-content mt-5">

    <div class="tab-pane fade" id="tab1" role="tabpanel">
        <!-- Pelayanan Rawat Jalan -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Waktu Pelayanan Rawat Jalan Tanpa Pemeriksaan Penunjang</h5>
				<!-- <span>
					Waktu pelayanan pasien rawat jalan tanpa pemeriksaan penunjang dihitung dari saat pasien check in di Poliklinik sampai dengan obat selesai disiapkan oleh Farmasi ≤ 120 menit
				</span> -->
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Waktu Pelayanan Rawat Jalan Tanpa Pemeriksaan Penunjang</span>
					<!-- <span class="text-muted mt-1 fw-bold fs-7">80% selesai di bawah 120 menit</span> -->
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
							<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkpirj"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade" id="tab2" role="tabpanel">
        <!-- Waktu Tunggu Rawat Jalan -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Waktu Tunggu Rawat Jalan</h5>
				<span>
					Waktu yang dibutuhkan mulai pasien kontak dengan petugas pendaftaran hingga mendapat pelayanan dari dokter/dokter spesialis
				</span>
			</div>
		</div>

		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-md-12">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Waktu Tunggu Rawat Jalan</span>
							<!-- <span class="text-muted mt-1 fw-bold fs-7">> 80% Pasien Rawat Jalan ≤ 60 menit</span> -->
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
									<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
						<div class="card-rounded-bottom" id="grafikkpiwaktutunggurajal"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Waktu Tunggu Check In - Mulai Anamnesa</span>
							<!-- <span class="text-muted mt-1 fw-bold fs-7">> 80% Waktu Tunggu Check In - Mulai Anamnesa ≤ 20 menit</span> -->
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
									<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
						<div class="card-rounded-bottom" id="grafikkpiwaktutunggurajalcheckinanam"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Waktu Tunggu Mulai - Selesai Anamnesa</span>
							<!-- <span class="text-muted mt-1 fw-bold fs-7">> 80% Waktu Tunggu Mulai - Selesai Anamnesa ≤ 10 menit</span> -->
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
									<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
						<div class="card-rounded-bottom" id="grafikkpiwaktutunggurajalanamnesa"></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Waktu Tunggu Selesai Anamnesa - Mulai Dokter</span>
							<!-- <span class="text-muted mt-1 fw-bold fs-7">> 80% Waktu Tunggu Selesai Anamnesa - Mulai Dokter ≤ 30 menit</span> -->
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
									<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body pt-0">
						<div class="card-rounded-bottom" id="grafikkpiwaktutunggurajaldokter"></div>
					</div>
				</div>
			</div>
		</div>
		
		
    </div>

    <div class="tab-pane fade" id="tab3" role="tabpanel">
        <!-- Pembatalan Operasi -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Pembatalan Operasi Elektif</h5>
				<span>
					Pembatalan kasus operasi elektif yang sudah terjadwal, namun batal dilakukan operasi pada hari H
				</span>
			</div>
		</div>

		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Pembatalan Operasi Elektif</span>
					<span class="text-muted mt-1 fw-bold fs-7">≤ 3% Pembatalan Operasi Elektif</span>
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
							<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkpioperasi"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade" id="tab4" role="tabpanel">
        <!-- Lama Layanan IGD -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Lama Layanan IGD</h5>
				<span>
					Waktu yang dihitung mulai dari pasien terdaftar masuk di IGD sampai keluar dari IGD  baik itu pulang atau rawat Inap atau Operasi ≤ dari 4 jam 
				</span>
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Lama Layanan IGD</span>
					<span class="text-muted mt-1 fw-bold fs-7">90% di bawah ≤ 4 jam</span>
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
							<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkpikeluarigd"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade" id="tab5" role="tabpanel">
        <!-- Waktu Masuk Rawat Inap -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Waktu Masuk Pasien Ranap</h5>
				<span>
					Waktu tunggu pasien masuk rawat inap dari rawat jalan dan IGD dimulai pada saat pasien terdaftar di admission rawat inap sampai dengan diterima oleh petugas di ruang rawat inap ≤ 60 menit.
					<ul>
						<li>Esklusi</li>
						<ul>
							<li>Transit</li>
							<li>Kamar Bayi</li>
							<li>NPP</li>
							<li>CC</li>
						</ul>
					</ul>
				</span>
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Waktu Masuk Pasien Ranap</span>
					<span class="text-muted mt-1 fw-bold fs-7">90% di bawah ≤ 60 menit</span>
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
							<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkpiwaktumasukranap"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade show active" id="tab6" role="tabpanel">
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-5 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Persentase Pasien Pulang</h5>
				<span>
					Pasien yang direncanakan pulang pada H-1 ( pulang sebelum pukul ≤ 12,00 waktu setempat di hari berikutnya) Target: ≥90%
					<ul>
						<li>Esklusi</li>
						<ul>
							<li>Pasien VIP</li>
							<li>Meninggal</li>
							<li>Pulang Atas Permintaan Sendiri</li>
						</ul>
					</ul>
				</span>
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Persentase Pasien Pulang</span>
					<span class="text-muted mt-1 fw-bold fs-7">≥ 90% Pasien Pulang Di Bawah Jam 12:00</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabpulangbulan">Mountly</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabpulangharian">Daily</a>
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
							<a href="#" class="menu-link px-3" id="btnDownloadExcelProviderIGD">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="tabpulangbulan" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkpipasienpulang"></div>
						<div class="card-rounded-bottom" id="grafikkpipasienpulangheatmap"></div>
					</div>
					<div id="tabpulangharian" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkpipasienpulangharian"></div>
						<div class="card-rounded-bottom" id="grafikkpipasienpulangharianheatmap"></div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>