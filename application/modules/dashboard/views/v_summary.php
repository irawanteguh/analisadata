<div class="col-xl-2 col-lg-4 col-md-6 mb-5" onclick="location.href='../dashboard/dashboard';" style="cursor:pointer;">
    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="fw-bolder fs-3 text-gray-800">Rawat Jalan</div>
                    <div class="fw-bold fs-8">Jumlah Kunjungan Pasien</div>
                </div>
                <span class="btn btn-icon btn-light-primary rounded-circle w-50px h-50px">
                    <i class="fas fa-hospital-user fs-2 text-primary"></i>
                </span>
            </div>
            <div class="mb-4">
                <div class="fw-bolder text-dark display-5">
                    <span id="jmlrawatjalansekarang">0</span>
                    <span class="fs-3 fw-semibold text-muted">Px</span>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-end border-top pt-3">
                <div>
                    <span id="presentasirawatjalansekarang" class="badge badge-light-success fw-bold fs-7 px-3 py-2">
                        <i class="bi bi-caret-up-fill"></i>
                        0%
                    </span>
                </div>
                <div class="text-end">
                    <div class="text-muted fs-8">
                        Last Period
                    </div>
                    <div id="jmlrawatjalansebelumnya" class="fw-bold fs-5 text-gray-700">
                        0 Px
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6 mb-5">
	<div class="card card-flush">
		<div class="card-header pt-5">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bolder fs-3 mb-1">Antrian Help Desk</span>
				<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Periode Tanggal</span>
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
						<a href="#" class="menu-link px-3" id="btndownloaddatahelpdesk_table">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body py-3">
			<div id="grafikantrianhelpdesk"></div>
		</div>
	</div>
</div>