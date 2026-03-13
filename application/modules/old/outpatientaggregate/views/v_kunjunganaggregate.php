<div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-12">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Aggregate Data OutPatient</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="card-rounded-bottom" id="grafikkunjunganaggregate" style="height: 300px"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Data OutPatient [DOKTER SPESIALIS]</span>
					<span class="text-muted mt-1 fw-bold fs-7">Kunjungan Rawat Jalan 1 Tahun Berdasarkan Dokter Spesialis</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a id="aggregatedoktertab" class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatedokter">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="rawdoktertab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawdokter">Raw Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="analisadoktertab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#analisadokter">Analysis</a>
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
							<a href="#" class="menu-link px-3">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="aggregatedokter" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="aggregatedoktertab">
						<div class="card-rounded-bottom" id="grafikkunjunganaggregatedokter"></div>
					</div>
					<div id="rawdokter" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="rawdoktertab">
						<div class="table-responsive mh-660px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">Provider</th>
										<th>JAN</th>
										<th>FEB</th>
										<th>MAR</th>
										<th>APR</th>
										<th>MEI</th>
										<th>JUN</th>
										<th>JUL</th>
										<th>AGS</th>
										<th>SEP</th>
										<th>OKT</th>
										<th>NOV</th>
										<th>DES</th>
										<th class="pe-4 rounded-end text-end">TOTAL</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="tablerawdokter"></tbody>
							</table>
						</div>
					</div>
					<div id="analisadokter" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="analisadoktertab">
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Data OutPatient [POLIKLINIK]</span>
					<span class="text-muted mt-1 fw-bold fs-7">Kunjungan Rawat Jalan 1 Tahun Berdasarkan Poliklinik</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a id="aggregatepolitab" class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregatepoli">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="rawpolitab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawpoli">Raw Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="analisapolitab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#analisapoli">Analysis</a>
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
							<a href="#" class="menu-link px-3">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="aggregatepoli" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="aggregatepolitab">
						<div class="card-rounded-bottom" id="grafikkunjunganaggregatepoli"></div>
					</div>
					<div id="rawpoli" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="rawpolitab">
						<div class="table-responsive mh-660px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">Provider</th>
										<th>JAN</th>
										<th>FEB</th>
										<th>MAR</th>
										<th>APR</th>
										<th>MEI</th>
										<th>JUN</th>
										<th>JUL</th>
										<th>AGS</th>
										<th>SEP</th>
										<th>OKT</th>
										<th>NOV</th>
										<th>DES</th>
										<th class="pe-4 rounded-end text-end">TOTAL</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="tablerawpoli"></tbody>
							</table>
						</div>
					</div>
					<div id="analisapoli" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="analisapolitab">
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="col-xl-6">
		<div class="card card-flush">
			<div class="card-header pt-5" id="">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Data OutPatient [PROVIDER]</span>
					<span class="text-muted mt-1 fw-bold fs-7">Kunjungan Rawat Jalan 1 Tahun Berdasarkan Provider</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a id="aggregateprovidertab" class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregateprovider">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="rawprovidertab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#rawprovider">Raw Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a id="analisaprovidertab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#analisaprovider">Analysis</a>
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
							<a href="#" class="menu-link px-3">Download Excel</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="tab-content">
					<div id="aggregateprovider" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="aggregateprovidertab">
						<div class="card-rounded-bottom" id="grafikkunjunganaggregateprovider"></div>
					</div>
					<div id="rawprovider" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="rawprovidertab">
						<div class="table-responsive mh-660px scroll-y me-n5 pe-5">
							<table class="table align-middle table-row-dashed fs-8 gy-2">
								<thead class="align-middle">
									<tr class="fw-bolder text-muted bg-light">
										<th class="ps-4 rounded-start">Provider</th>
										<th>JAN</th>
										<th>FEB</th>
										<th>MAR</th>
										<th>APR</th>
										<th>MEI</th>
										<th>JUN</th>
										<th>JUL</th>
										<th>AGS</th>
										<th>SEP</th>
										<th>OKT</th>
										<th>NOV</th>
										<th>DES</th>
										<th class="pe-4 rounded-end text-end">TOTAL</th>
									</tr>
								</thead>
								<tbody class="text-gray-600 fw-bold" id="tablerawprovider"></tbody>
							</table>
						</div>
					</div>
					<div id="analisaprovider" class="card-body p-0 tab-pane fade" role="tabpanel" aria-labelledby="analisaprovidertab">
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>