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
                        <a class="nav-link" data-bs-toggle="tab" href="#tab1">Waktu Pelayanan Rawat Jalan</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab2">Rawat Jalan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab3">Pembatalan Operasi Elektif</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab4">Lama Layanan IGD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab5">Waktu Masuk Pasien Ranap</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link " data-bs-toggle="tab" href="#tab6">Realisasi Pasien Pulang H-1</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab7">Pemeriksaan Konvensional Non Kontras</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="tab-content mt-5">

    <div class="tab-pane fade" id="tab1" role="tabpanel">
        <!-- Pelayanan Rawat Jalan -->
        <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-5 fa-fade">
			<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
					<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
				</svg>
			</span>
			<div class="d-flex flex-column pe-0 pe-sm-10">
				<h5 class="mb-1">Waktu Pelayanan Rawat Jalan Tanpa Pemeriksaan Penunjang</h5>
				<span>
					Waktu pelayanan pasien rawat jalan tanpa pemeriksaan penunjang dihitung dari saat pasien check in di Poliklinik sampai dengan obat selesai disiapkan oleh Farmasi ≤ 120 menit
				</span>
			</div>
		</div>
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Waktu Pelayanan Rawat Jalan</span>
					<span class="text-muted mt-1 fw-bold fs-7">≥80% di bawah 120 menit (Khusus Mata dan Ortopedi 180 menit)</span>
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
			<div class="card-body py-3">
				<div class="card-rounded-bottom" id="grafikkpirj"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade show active" id="tab2" role="tabpanel">
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
					Waktu yang dibutuhkan mulai pasien kontak dengan petugas pendaftaran hingga mendapat pelayanan dari dokter/dokter spesialis.
					<ul>
						<li>Eksklusi:</li>
						<ul>
							<li>Poli Rehabilitasi Medik, Fisioterapi, Medical Check Up, Poli Gigi Umum, Poli Gigi Spesialis, Poli Gigi Spesialis Orthodonti, Poli Gigi Spesialis Endodonsi, Poli Gigi Spesialis Bedah Mulut, Poli Gigi Spesialis Pedodontis, Poli Gigi Spesialis Prostodonsia, Haemodialisa, CAPD, Terapi Wicara, Okupasi Terapi.</li>
						</ul>
					</ul>
				</span>
			</div>
		</div>

		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-md-12">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">Waktu Tunggu Rawat Jalan</span>
							<span class="text-muted mt-1 fw-bold fs-7">≥80% di bawah 60 menit</span>
						</h3>
					</div>
					<div class="card-body py-3">
						<div class="card-rounded-bottom" id="kpirawatjalanwaktutunggu"></div>
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
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabkpiokmountly">Mountly</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabkpiokdaily">Daily</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="tabkpiokmountly" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkpiokmountly"></div>
					</div>
					<div id="tabkpiokdaily" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkpiokdaily"></div>
					</div>
				</div>
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
			<div class="card-body py-3">
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
			<div class="card-body py-3">
				<div class="card-rounded-bottom" id="grafikkpiwaktumasukranap"></div>
			</div>
		</div>
    </div>

    <div class="tab-pane fade" id="tab6" role="tabpanel">
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

		<div class="row">
			<div class="col-xl-12 mb-5">
				<div class="card card-flush">
					<div class="card-header pt-5">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1">KPI Jam Pasien Pulang &lt; 12:00 WIB</span>
							<span class="text-muted mt-1 fw-bold fs-7">Pasien yang direncanakan pulang pada H-1 ( pulang sebelum pukul ≤ 12,00 waktu setempat di hari berikutnya) Target: ≥90%</span>
						</h3>
						<div class="card-toolbar m-0">
							<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabkpirawatinapall">ALL</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabkpirawatinaptutd">TUTD</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabkpirawatinapnontutd">NON TUTD</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="card-body py-3">
						<div class="tab-content">
							<div id="tabkpirawatinapall" class="card-body p-0 tab-pane fade show active" role="tabpanel">
								<div class="card-rounded-bottom" id="kpirawatinapall"></div>
								<div class="card-rounded-bottom" id="kpirawatinapalldaily"></div>
							</div>
							<div id="tabkpirawatinaptutd" class="card-body p-0 tab-pane fade" role="tabpanel">
								<div class="card-rounded-bottom" id="kpirawatinaptutd"></div>
								<div class="card-rounded-bottom" id="kpirawatinaptutddaily"></div>
							</div>
							<div id="tabkpirawatinapnontutd" class="card-body p-0 tab-pane fade" role="tabpanel">
								<div class="card-rounded-bottom" id="kpirawatinapnontutd"></div>
								<div class="card-rounded-bottom" id="kpirawatinapnontutddaily"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-12">
			<div class="row">

				<!-- Discharge Planning Kelas 1 -->
				<div class="col-xl-4 col-lg-4 col-md-6 mb-5">
					<div class="card border border-primary bg-light-primary shadow-sm h-100">
						<div class="card-body p-5">
							<div class="d-flex align-items-center mb-4">
								<div class="symbol symbol-50px me-4">
									<span class="symbol-label bg-primary">
										<i class="fas fa-procedures fs-2x text-white"></i>
									</span>
								</div>
								<div>
									<div class="fw-bold fs-4 text-primary">
										Discharge Planning Kelas 1
									</div>
									<div class="text-muted fs-7">
										Jumlah pasien kelas 1
									</div>
								</div>
							</div>

							<div class="d-flex align-items-baseline">
								<span id="jml_discharge_kelas1"
									class="fw-bolder fs-2x text-primary">0</span>
								<span class="text-muted fw-semibold ms-2">Pasien</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Discharge Planning Kelas 2 -->
				<div class="col-xl-4 col-lg-4 col-md-6 mb-5">
					<div class="card border border-info bg-light-info shadow-sm h-100">
						<div class="card-body p-5">
							<div class="d-flex align-items-center mb-4">
								<div class="symbol symbol-50px me-4">
									<span class="symbol-label bg-info">
										<i class="fas fa-procedures fs-2x text-white"></i>
									</span>
								</div>
								<div>
									<div class="fw-bold fs-4 text-info">
										Discharge Planning Kelas 2
									</div>
									<div class="text-muted fs-7">
										Jumlah pasien kelas 2
									</div>
								</div>
							</div>

							<div class="d-flex align-items-baseline">
								<span id="jml_discharge_kelas2"
									class="fw-bolder fs-2x text-info">0</span>
								<span class="text-muted fw-semibold ms-2">Pasien</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Discharge Planning Kelas 3 -->
				<div class="col-xl-4 col-lg-4 col-md-6 mb-5">
					<div class="card border border-success bg-light-success shadow-sm h-100">
						<div class="card-body p-5">
							<div class="d-flex align-items-center mb-4">
								<div class="symbol symbol-50px me-4">
									<span class="symbol-label bg-success">
										<i class="fas fa-procedures fs-2x text-white"></i>
									</span>
								</div>
								<div>
									<div class="fw-bold fs-4 text-success">
										Discharge Planning Kelas 3
									</div>
									<div class="text-muted fs-7">
										Jumlah pasien kelas 3
									</div>
								</div>
							</div>

							<div class="d-flex align-items-baseline">
								<span id="jml_discharge_kelas3"
									class="fw-bolder fs-2x text-success">0</span>
								<span class="text-muted fw-semibold ms-2">Pasien</span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>


		<div class="col-xl-6 mb-5">
			<div class="card card-flush">
				<div class="card-header pt-5">
					<div class="card-title">
						<div class="d-flex align-items-center position-relative my-1">
							<span class="svg-icon svg-icon-1 position-absolute ms-6">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
									<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
								</svg>
							</span>
							<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Data" id="searchtable">
						</div>
					</div>
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
								<a href="#" class="menu-link px-3" id="btnDownloadExcelKunjungan">Download Excel</a>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body py-3">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed gy-2 fs-8" id="datapasienpulanglebihjam12_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>Patient Name</th>
									<th>Doctor</th>
									<th>Room</th>
									<th>Class</th>
									<th>Discharge Date</th>
									<th>Status</th>
									<th class="pe-4 rounded-end text-end">Actions</th>
								</tr>
							</thead>
							<tbody class="fw-bold text-gray-600" id="resultdatapasienpulanglebihjam12"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
    </div>

	<div class="tab-pane fade" id="tab7" role="tabpanel">
		<div class="col-xl-12 mb-5">
			<div class="card card-flush">
				<div class="card-header pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">KPI Waktu Pelayanan Radiologi ≤ 60 Menit</span>
						<span class="text-muted mt-1 fw-bold fs-7">Pemeriksaan Konvensional Non Kontras, dengan Target Penyelesaian ≤ 60 Menit</span>
					</h3>
				</div>
				<div class="card-body py-3">
					<div class="card-rounded-bottom" id="grafikradiologi"></div>
				</div>
			</div>
		</div>
    </div>
</div>