<div class="row">
	<div class="col-xl-8 mb-5">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Pasien</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabmcu">MCU</a>
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
							<a href="#" class="menu-link px-3" id="btndownloaddatakunjungan_table">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="tabmcu" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganmcu"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 mb-5">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan By Provider</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Provider</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabchartmcuprovider">MCU</a>
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
							<a href="#" class="menu-link px-3" id="btndownloaddatakunjunganprovider_table">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="tabchartmcuprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganmcuprovider"></div>
					</div>
				</div>
			</div>
		</div>
	</div> 
</div>

<div class="col-xl-12 mb-5">
	<div class="card card-flush">
		<div class="card-header pt-5">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Medical Check Up</span>
				<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabpaketmcu">Paket MCU</a>
					</li>
					<li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabprovidermcu">Provider</a>
                    </li>
					<li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabrawdata">Raw Data MCU</a>
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
						<a href="#" class="menu-link px-3" id="btndownloaddatadetailmcu_table">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div class="tab-content">
				<div id="tabpaketmcu" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datapaketmcu_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>NAMA PAKET</th>
									<th>JAN</th>
									<th>FEB</th>
									<th>MAR</th>
									<th>APR</th>
									<th>MEI</th>
									<th>JUN</th>
									<th>JUL</th>
									<th>AUG</th>
									<th>SEP</th>
									<th>OKT</th>
									<th>NOV</th>
									<th>DES</th>	
									<th class="pe-4 rounded-end text-end">TOTAL</th>
								</tr>
							</thead>
							<tbody class="text-gray-600 fw-bold" id="resultdatapaketmcu"></tbody>
							<tfoot id="footerdatapaketmcu"></tfoot>
						</table>
					</div>
				</div>
				<div id="tabprovidermcu" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="dataprovidermcu_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>PROVIDER</th>
                                    <th>JAN</th>
                                    <th>FEB</th>
                                    <th>MAR</th>
                                    <th>APR</th>
                                    <th>MEI</th>
                                    <th>JUN</th>
                                    <th>JUL</th>
                                    <th>AUG</th>
                                    <th>SEP</th>
                                    <th>OKT</th>
                                    <th>NOV</th>
                                    <th>DES</th>
                                    <th class="pe-4 rounded-end text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="resultdataprovidermcu"></tbody>
                            <tfoot id="footerdataprovidermcu"></tfoot>
                        </table>
                    </div>
                </div>
				<div id="tabrawdata" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="datamcudetail_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>MR PASIEN</th>
                                    <th>NAMA PASIEN</th>
                                    <th>TGL MASUK</th>
                                    <th>PROVIDER</th>
                                    <th class="pe-4 rounded-end text-end">NAMA PAKET MCU</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="resultdatamcudetail"></tbody>
                        </table>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>