<div class="row gy-5 g-xl-8 mb-xl-8">
	<div class="col-xl-3">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Notifications</span>
					<span class="text-muted mt-1 fw-bold fs-7">Klik Untuk Melihat Detail</span>
				</h3>
			</div>
			<div class="card-body pt-5">
				<div class="d-flex align-items-center bg-light-warning rounded p-5 mb-3">
					<span class="svg-icon svg-icon-warning me-5">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black"></path>
								<path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black"></path>
							</svg>
						</span>
					</span>
					<div class="flex-grow-1 me-2">
						<a href="#" class="fw-bolder text-gray-800 text-hover-primary fs-6" data-kt-drawer-show="true" data-kt-drawer-target="#drawer_transit">Pasien Transit</a>
						<span class="text-muted fw-bold d-block">Total Pasien Rawat Inap Transit</span>
					</div>
					<span class="fw-bolder text-warning py-1" id="totalpasientransit"></span>
				</div>
				<!-- <div class="d-flex align-items-center bg-light-success rounded p-5 mb-3">
					<span class="svg-icon svg-icon-success me-5">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black"></path>
								<path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black"></path>
							</svg>
						</span>
					</span>
					<div class="flex-grow-1 me-2">
						<a href="#" class="fw-bolder text-gray-800 text-hover-primary fs-6">Pasien Re Admisi</a>
						<span class="text-muted fw-bold d-block">Due in 2 Days</span>
					</div>
					<span class="fw-bolder text-success py-1">+50%</span>
				</div> -->

				<!-- <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-3">
					<span class="svg-icon svg-icon-danger me-5">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black"></path>
								<path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black"></path>
							</svg>
						</span>
					</span>
					<div class="flex-grow-1 me-2">
						<a href="#" class="fw-bolder text-gray-800 text-hover-primary fs-6" data-kt-drawer-show="true" data-kt-drawer-target="#drawer_pasienmeninggal">Pasien Meninggal Dunia</a>
						<span class="text-muted fw-bold d-block" id="descpasienmeninggal">Loading</span>
					</div>
					<span class="fw-bolder text-danger py-1" id="totalpasienmeninggal"></span>
				</div> -->

				<!-- <div class="d-flex align-items-center bg-light-info rounded p-5 mb-3">
					<span class="svg-icon svg-icon-info me-5">
						<span class="svg-icon svg-icon-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black"></path>
								<path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black"></path>
							</svg>
						</span>
					</span>
					<div class="flex-grow-1 me-2">
						<a href="#" class="fw-bolder text-gray-800 text-hover-primary fs-6">Pending Resume</a>
						<span class="text-muted fw-bold d-block">Due in 2 Days</span>
					</div>
					<span class="fw-bolder text-info py-1">+50%</span>
				</div> -->
			</div>
		</div>
	</div>
	<div class="col-xl-9"></div>
    <!-- <div class="col-xl-3">
        <a href="#" class="card bg-info hoverable card-xl-stretch mb-xl-8" id="btnPasienTransit">
            <div class="card-body">
                <i class="fa-solid fa-procedures text-white fa-3x"></i>
                <div class="text-white fw-bolder fs-2 mb-0 mt-5">Pasien Transit</div>
                <div class="fw-bold text-white" id="totalpasientransit">Total Pasien Transit : 0 Px</div>
            </div>
        </a>
    </div>
	<div class="col-xl-3">
        <a href="#" class="card bg-info hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <i class="fa-solid fa-procedures text-white fa-3x"></i>
                <div class="text-white fw-bolder fs-2 mb-0 mt-5">Pasien Re Admisi</div>
                <div class="fw-bold text-white" id="totalpasientransit">Total Pasien Transit : 0 Px</div>
            </div>
        </a>
    </div>
	<div class="col-xl-3">
        <a href="#" class="card bg-info hoverable card-xl-stretch mb-xl-8">
            <div class="card-body">
                <i class="bi bi-person-wheelchair text-white fa-3x"></i>
                <div class="text-white fw-bolder fs-2 mb-0 mt-5">Pasien Meninggal Dunia</div>
                <div class="fw-bold text-white" id="totalpasienmeninggal">Total Pasien Meninggal Dunia Kemarin : 0 Px</div>
            </div>
        </a>
    </div>
    <div class="col-xl-3">
    </div> -->
	<div class="col-xl-9">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan IGD</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Tanggal Masuk</span>
				</h3>
				<div class="card-toolbar m-0">
					<ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#aggregagatekunjunganigd">Aggregate Data</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#aikunjunganigd">AI Analysis</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="card-body pt-0">
				<div class="tab-content">
					<div id="aggregagatekunjunganigd" class="card-body p-0 tab-pane fade show active" role="tabpanel">
						<div class="card-rounded-bottom" id="grafikkunjunganigd"></div>
					</div>
					<div id="aikunjunganigd" class="card-body p-0 tab-pane fade" role="tabpanel">
						<textarea name="analisaaikunjunganigd" id="analisaaikunjunganigd" class="form-control" rows="11" readonly></textarea>
						<br>
						<a class="btn btn-light-primary btn-sm" href="javascript:void(0)" onclick="analisaaikunjunganigd()"><i class="bi bi-stars"></i> Generate Analysis AI</a>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="col-xl-3">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan IGD</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Provider</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkunjunganigdprovider"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-9">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Jalan</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Tanggal Masuk</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkunjunganrj"></div>
			</div>
		</div>
	</div>
    <div class="col-xl-3">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Jalan</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Provider</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkunjunganrjprovider"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-9">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Inap</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Tanggal Masuk</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkunjunganri"></div>
			</div>
		</div>
	</div>
    <div class="col-xl-3">
		<div class="card card-flush">
			<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Kunjungan Rawat Inap</span>
					<span class="text-muted mt-1 fw-bold fs-7">Berdasarkan Provider</span>
				</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikkunjunganriprovider"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-4">
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<div class="card-header border-0">
				<h3 class="card-title fw-bolder text-dark">Demografi</h3>
			</div>
			<div class="card-body pt-0">
				<div class="card-rounded-bottom" id="grafikumur"></div>
			</div>
		</div>
	</div>
</div>