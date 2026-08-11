<div class="col-xl-12 mb-5">
	<div class="card card-flush">
		<div class="card-header pt-5" id="">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bolder fs-3 mb-1">Perhitungan Uang Shifting</span>
				<span class="text-muted mt-1 fw-bold fs-7">Laporan Perhitungan Uang Shifting</span>
			</h3>
			<div class="card-toolbar m-0">
				<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#rawdata">Raw Data</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#byname">By Name</a>
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
						<a href="#" class="menu-link px-3" id="btndownloaddatashift_table">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div class="tab-content">
				<div id="rawdata" class="card-body p-0 tab-pane fade show active" role="tabpanel">
					<div class="table-responsive">
						<table class="table align-middle table-row-dashed fs-8 gy-2" id="dataperhitunganshift_table">
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
				<div id="byname" class="card-body p-0 tab-pane fade" role="tabpanel">
					<table class="table align-middle table-row-dashed fs-8 gy-2" id="dataperhitunganshiftname_table">
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