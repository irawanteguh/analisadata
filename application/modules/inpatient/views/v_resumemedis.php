<div class="row gy-5 g-xl-8 mb-xl-8">
    <div class="col-xl-12">
        <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/images/svg/misc/taieri.svg') ?>');">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
					<div>
						<h1>Laporan Resume Medis Rawat Inap</h1>
						<p class="mb-0">
							Monitoring kelengkapan dan ketepatan waktu penyelesaian resume medis pasien rawat inap sebagai bagian dari pengendalian mutu rekam medis dan kepatuhan terhadap standar pelayanan rumah sakit.
						</p>
					</div>
				</div>
                <div class="d-flex overflow-auto min-h-30px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
						<li class="nav-item">
							<a class="nav-link text-muted active" data-bs-toggle="tab" href="#summary">Summary</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#indikatormutu">Indikator Mutu</a>
						</li>
                        <li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#resumepending">Resume Pending</a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content mt-5">
	<div class="tab-pane fade active show" id="summary" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-xl-3">
				<a href="#" class="card bg-info hoverable card-xl-stretch mb-xl-8">
					<div class="card-body">
						<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M14 12V21H10V12C10 11.4 10.4 11 11 11H13C13.6 11 14 11.4 14 12ZM7 2H5C4.4 2 4 2.4 4 3V21H8V3C8 2.4 7.6 2 7 2Z" fill="black"></path>
								<path d="M21 20H20V16C20 15.4 19.6 15 19 15H17C16.4 15 16 15.4 16 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="black"></path>
							</svg>
						</span>
						<div class="text-white fw-bolder fs-2 mb-0 mt-5">Pasien Pulang</div>
						<div class="fw-bold text-white" id="totalpasienpulang">Total Pasien Pulang Rawat Inap : 0 Px</div>
					</div>
				</a>
			</div>
			<div class="col-xl-3">
				<a href="#" class="card bg-success hoverable card-xl-stretch mb-xl-8">
					<div class="card-body">
						<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M14 12V21H10V12C10 11.4 10.4 11 11 11H13C13.6 11 14 11.4 14 12ZM7 2H5C4.4 2 4 2.4 4 3V21H8V3C8 2.4 7.6 2 7 2Z" fill="black"></path>
								<path d="M21 20H20V16C20 15.4 19.6 15 19 15H17C16.4 15 16 15.4 16 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="black"></path>
							</svg>
						</span>
						<div class="text-white fw-bolder fs-2 mb-0 mt-5">Resume Selesai</div>
						<div class="fw-bold text-white" id="totalresume">Total Resume Yang Telah Di Buat</div>
					</div>
				</a>
			</div>
			<div class="col-xl-3">
				<a href="#" class="card bg-primary hoverable card-xl-stretch mb-5 mb-xl-8">
					<div class="card-body">
						<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M14 12V21H10V12C10 11.4 10.4 11 11 11H13C13.6 11 14 11.4 14 12ZM7 2H5C4.4 2 4 2.4 4 3V21H8V3C8 2.4 7.6 2 7 2Z" fill="black"></path>
								<path d="M21 20H20V16C20 15.4 19.6 15 19 15H17C16.4 15 16 15.4 16 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="black"></path>
							</svg>
						</span>
						<div class="text-white fw-bolder fs-2 mb-0 mt-5">Pending Resume <= 48 Jam</div>
						<div class="fw-bold text-white" id="pendingresumekurang">Pending Resume Medis <= 48 Jam : 0 Px</div>
					</div>
				</a>
			</div>
			<div class="col-xl-3">
				<a href="#" class="card bg-danger hoverable card-xl-stretch mb-5 mb-xl-8">
					<div class="card-body">
						<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M14 12V21H10V12C10 11.4 10.4 11 11 11H13C13.6 11 14 11.4 14 12ZM7 2H5C4.4 2 4 2.4 4 3V21H8V3C8 2.4 7.6 2 7 2Z" fill="black"></path>
								<path d="M21 20H20V16C20 15.4 19.6 15 19 15H17C16.4 15 16 15.4 16 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z" fill="black"></path>
							</svg>
						</span>
						<div class="text-white fw-bolder fs-2 mb-0 mt-5">Pending Resume > 48 Jam</div>
						<div class="fw-bold text-white" id="pendingresumelebih">Pending Resume > 48 Jam : 0 Px</div>
					</div>
				</a>
			</div>
			<div class="col-xl-9">
				<div class="card card-flush">
					<div class="card-body">
						<div id="grafikresumemedis"></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3">
				<div class="card card-flush">
					<div class="card-body">
						<div id="grafikresumemedisglobal"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="indikatormutu" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-xl-12">
				<div class="card card-flush">
					<div class="card-body">
						<div id="grafikresumemedisharian"></div>
					</div>
				</div>
			</div>
			<div class="col-xl-12">
				<div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/images/svg/misc/eolic-energy.svg') ?>');">
					<div class="card-body pt-9 pb-0">
						<div class="d-flex flex-wrap flex-sm-nowrap mb-5">
							<div>
								<h1>Periode Laporan</h1>
							</div>
						</div>

						<div class="d-flex overflow-auto min-h-30px">
							<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
								<li class="nav-item"><a class="nav-link text-muted active" data-bs-toggle="tab" href="#januari">Januari</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#februari">Februari</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#maret">Maret</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#april">April</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#mei">Mei</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#juni">Juni</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#juli">Juli</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#agustus">Agustus</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#september">September</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#oktober">Oktober</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#november">November</a></li>
								<li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#desember">Desember</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-content mt-5">
				<?php

					$bulan = [
						"01"=>"Januari",
						"02"=>"Februari",
						"03"=>"Maret",
						"04"=>"April",
						"05"=>"Mei",
						"06"=>"Juni",
						"07"=>"Juli",
						"08"=>"Agustus",
						"09"=>"September",
						"10"=>"Oktober",
						"11"=>"November",
						"12"=>"Desember"
					];

					foreach($bulan as $kode=>$nama){
						$active = ($kode == "01") ? "show active" : "";
					?>
					<div class="tab-pane fade <?= $active ?>" id="<?= strtolower($nama) ?>" role="tabpanel">
						<div class="card card-flush">
							<div class="card-header pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bolder fs-3 mb-1"><?= $nama ?></span>
								</h3>
								<div class="card-toolbar m-0">
									<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3"
									data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
										<span class="svg-icon svg-icon-3 svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000"/>
													<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
													<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
													<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
												</g>
											</svg>
										</span>
									</button>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
										<div class="menu-item px-3">
											<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">More Actions</div>
										</div>
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3"
											onclick="exportTableToExcel('tabledata<?= $kode ?>', 'Laporan Resume Medis Rawat Inap <?= $nama ?>')">
											Download Excel
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive mh-610px">
									<table class="table align-middle table-row-dashed fs-8 gy-2" id="tabledata<?= $kode ?>">
										<thead class="align-middle text-center">
											<tr class="fw-bolder text-muted bg-light">
												<th class="rounded-start">#</th>
												<th>Tanggal</th>
												<th>Resume Medis Belum Selesai</th>
												<th>Resume Medis Sudah Selesai</th>
												<th>Data Pasien Pulang Inap</th>
												<th class="pe-4 text-end rounded-end">Presentasi</th>
											</tr>
										</thead>
										<tbody class="text-gray-600 fw-bold" id="resultdatabln<?= $kode ?>"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="resumepending" role="tabpanel">
		<div class="row gy-5 g-xl-5 mb-xl-5">
			<div class="col-xl-12">
				<?php      
					include_once(APPPATH."views/template/search.php");
				?>
				<div class="card card-flush">
					<div class="card-header pt-5" id="">
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bolder fs-3 mb-1"></span>
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
									<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tablerawdatapendingresume', 'Laporan Resume Medis Rawat Inap')">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive mh-610px">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tablerawdatapendingresume">
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
</div>