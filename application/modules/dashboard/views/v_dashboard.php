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
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabigd">IGD</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabrjall">Rawat Jalan All</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabrj">Rawat Jalan Non Executive</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabexecutive">Rawat Jalan Executive</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabmcu">MCU</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabri">Rawat Inap</a>
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
					<div id="tabigd" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganigd"></div>
					</div>
					<div id="tabrjall" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganrjall"></div>
					</div>
					<div id="tabrj" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganrjnonexecutive"></div>
					</div>
					<div id="tabexecutive" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganexecutive"></div>
					</div>
					<div id="tabmcu" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganmcu"></div>
					</div>
					<div id="tabri" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganri"></div>
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
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabchartigdprovider">IGD</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabchartrjprovider">Rawat Jalan</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabchartriprovider">Rawat Inap</a>
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
					<div id="tabchartigdprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganigdprovider"></div>
					</div>
					<div id="tabchartrjprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganrjprovider"></div>
					</div>
					<div id="tabchartriprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganriprovider"></div>
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
				<span class="card-label fw-bolder fs-3 mb-1">Kunjungan IGD Per Bulan</span>
				<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<!-- <li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 " data-bs-toggle="tab" role="tab" href="#tabigddokter">Dokter</a>
					</li> -->
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabigdprovider">Provider</a>
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
						<a href="#" class="menu-link px-3" id="btndownloaddatadetailigd_table">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div class="tab-content">
				<!-- <div id="tabigddokter" class="card-body p-0 tab-pane fade" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datadokterigd_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>NAMA DOKTER</th>
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
							<tbody class="text-gray-600 fw-bold" id="resultdatadokterigd"></tbody>
							<tfoot id="footerdatadokterigd"></tfoot>
						</table>
					</div>
				</div> -->
				<div id="tabigdprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="dataigdprovider_table">
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
							<tbody class="text-gray-600 fw-bold" id="resultdataigdprovider"></tbody>
							<tfoot id="footerdataigdprovider"></tfoot>
						</table>
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
				<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Jalan Per Bulan Non Executive dan Executive</span>
				<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabpoli">Poliklinik</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabdokter">Dokter</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabprovider">Provider</a>
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
						<a href="#" class="menu-link px-3" id="btndownloaddatadetailrj_table">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div class="tab-content">
				<div id="tabpoli" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datapoliklinik_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>POLIKLINIK</th>
									<th class="text-end">JAN</th>
									<th class="text-end">FEB</th>
									<th class="text-end">MAR</th>
									<th class="text-end">APR</th>
									<th class="text-end">MEI</th>
									<th class="text-end">JUN</th>
									<th class="text-end">JUL</th>
									<th class="text-end">AUG</th>
									<th class="text-end">SEP</th>
									<th class="text-end">OKT</th>
									<th class="text-end">NOV</th>
									<th class="text-end">DES</th>	
									<th class="pe-4 rounded-end text-end">TOTAL</th>
								</tr>
							</thead>
							<tbody class="text-gray-600 fw-bold" id="resultdatapoliklinik"></tbody>
							<tfoot id="footerdatapoliklinik"></tfoot>
						</table>
					</div>
				</div>
				<div id="tabdokter" class="card-body p-0 tab-pane fade" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datadokter_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>NAMA DOKTER</th>
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
							<tbody class="text-gray-600 fw-bold" id="resultdatadokter"></tbody>
							<tfoot id="footerdatadokter"></tfoot>
						</table>
					</div>
				</div>
				<div id="tabprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="dataprovider_table">
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
							<tbody class="text-gray-600 fw-bold" id="resultdataprovider"></tbody>
							<tfoot id="footerdataprovider"></tfoot>
						</table>
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
                <span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Jalan Per Bulan Non Executive</span>
                <span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
            </h3>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabpolinonexecutive">Poliklinik</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabdokternonexecutive">Dokter</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabprovidernonexecutive">Provider</a>
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
                        <a href="#" class="menu-link px-3" id="btndownloaddatadetailrjnonexecutive_table">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="tab-content">
                <div id="tabpolinonexecutive" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="datapolikliniknonexecutive_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>POLIKLINIK</th>
                                    <th class="text-end">JAN</th>
                                    <th class="text-end">FEB</th>
                                    <th class="text-end">MAR</th>
                                    <th class="text-end">APR</th>
                                    <th class="text-end">MEI</th>
                                    <th class="text-end">JUN</th>
                                    <th class="text-end">JUL</th>
                                    <th class="text-end">AUG</th>
                                    <th class="text-end">SEP</th>
                                    <th class="text-end">OKT</th>
                                    <th class="text-end">NOV</th>
                                    <th class="text-end">DES</th>
                                    <th class="pe-4 rounded-end text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="resultdatapolikliniknonexecutive"></tbody>
                            <tfoot id="footerdatapolikliniknonexecutive"></tfoot>
                        </table>
                    </div>
                </div>
                <div id="tabdokternonexecutive" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="datadokternonexecutive_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>NAMA DOKTER</th>
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
                            <tbody class="text-gray-600 fw-bold" id="resultdatadokternonexecutive"></tbody>
                            <tfoot id="footerdatadokternonexecutive"></tfoot>
                        </table>
                    </div>
                </div>
                <div id="tabprovidernonexecutive" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="dataprovidernonexecutive_table">
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
                            <tbody class="text-gray-600 fw-bold" id="resultdataprovidernonexecutive"></tbody>
                            <tfoot id="footerdataprovidernonexecutive"></tfoot>
                        </table>
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
                <span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Jalan Per Bulan Executive</span>
                <span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
            </h3>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabpoliexecutive">Poliklinik</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabdokterexecutive">Dokter</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabproviderexecutive">Provider</a>
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
                        <a href="#" class="menu-link px-3" id="btndownloaddatadetailrjexecutive_table">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="tab-content">
                <div id="tabpoliexecutive" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="datapoliklinikexecutive_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>POLIKLINIK</th>
                                    <th class="text-end">JAN</th>
                                    <th class="text-end">FEB</th>
                                    <th class="text-end">MAR</th>
                                    <th class="text-end">APR</th>
                                    <th class="text-end">MEI</th>
                                    <th class="text-end">JUN</th>
                                    <th class="text-end">JUL</th>
                                    <th class="text-end">AUG</th>
                                    <th class="text-end">SEP</th>
                                    <th class="text-end">OKT</th>
                                    <th class="text-end">NOV</th>
                                    <th class="text-end">DES</th>
                                    <th class="pe-4 rounded-end text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="resultdatapoliklinikexecutive"></tbody>
                            <tfoot id="footerdatapoliklinikexecutive"></tfoot>
                        </table>
                    </div>
                </div>
                <div id="tabdokterexecutive" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="datadokterexecutive_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>NAMA DOKTER</th>
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
                            <tbody class="text-gray-600 fw-bold" id="resultdatadokterexecutive"></tbody>
                            <tfoot id="footerdatadokterexecutive"></tfoot>
                        </table>
                    </div>
                </div>
                <div id="tabproviderexecutive" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2" id="dataproviderexecutive_table">
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
                            <tbody class="text-gray-600 fw-bold" id="resultdataproviderexecutive"></tbody>
                            <tfoot id="footerdataproviderexecutive"></tfoot>
                        </table>
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
			</div>
		</div>
	</div>
</div>

<div class="col-xl-12 mb-5">
	<div class="card card-flush">
		<div class="card-header pt-5">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Inap Per Bulan</span>
				<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal Masuk</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabridokter">Dokter</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabriprovider">Provider</a>
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
				<div id="tabridokter" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datadokterri_table">
							<thead class="align-middle">
								<tr class="fw-bolder text-muted bg-light">
									<th class="ps-4 rounded-start">#</th>
									<th>NAMA DOKTER</th>
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
							<tbody class="text-gray-600 fw-bold" id="resultdataridokter"></tbody>
							<tfoot id="footerdataridokter"></tfoot>
						</table>
					</div>
				</div>
				<div id="tabriprovider" class="card-body p-0 tab-pane fade" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="datariprovider_table">
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
							<tbody class="text-gray-600 fw-bold" id="resultdatariprovider"></tbody>
							<tfoot id="footerdatariprovider"></tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>