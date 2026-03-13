<div class="row gy-5 g-xl-8 mb-xl-8">
    <div class="col-xl-12 border">
        <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/images/svg/misc/taieri.svg') ?>');">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div>
                        <h1>Perhitungan Uang Shifting</h1>
                        <p class="mb-0">
                            Laporan Perhitungan Uang Shifting
                        </p>
                    </div>
                </div>
                <div class="d-flex overflow-auto min-h-30px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
						<li class="nav-item">
							<a class="nav-link text-muted active" data-bs-toggle="tab" href="#rawdata">Raw Data</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#namedata">By Name</a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-content mt-5">
	<div class="tab-pane fade active show" id="rawdata" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
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
									<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tableperhitunganshift', 'Detail Laporan Shift')">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-8">
						<div class="table-responsive">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tableperhitunganshift">
								<thead>
									<tr class="fw-bolder text-muted bg-light align-middle">
										<th class="ps-4 rounded-start">#</th>
										<th>TANGGAL</th>
										<th>HARI</th>
										<th>NIK</th>
										<th>NAMA</th>
                                        <th>UNIT</th>
                                        <th>SUB UNIT</th>
                                        <th>KATEGORI</th>
                                        <th>FLAG</th>
                                        <th class="text-end">JADWAL MASUK</th>
                                        <th class="text-end">JADWAL PULANG</th>
                                        <th class="text-end">JAM MASUK</th>
                                        <th class="text-end">JAM PULANG</th>
										<th class="text-end">UANG SHIFT</th>
										<th class="pe-4 text-end rounded-end">KETERANGAN</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultdataperhitunganshift"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="tab-pane fade" id="namedata" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
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
									<a href="#" class="menu-link px-3" onclick="exportTableToExcel('tableperhitunganshiftname', 'Rekap Laporan Shift')">Download Excel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-8">
						<div class="table-responsive">
							<table class="table align-middle table-row-dashed fs-8 gy-2" id="tableperhitunganshiftname">
								<thead>
									<tr class="fw-bolder text-muted bg-light align-middle">
										<th class="ps-4 rounded-start">#</th>
										<th>NIK</th>
										<th>NAMA</th>
                                        <th>UNIT</th>
                                        <th>SUB UNIT</th>
                                        <th>KATEGORI</th>
                                        <th>JUMLAH UANG SHIFT</th>
										<th class="pe-4 text-end rounded-end">ACTIONS</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="resultdataperhitunganshiftname"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>