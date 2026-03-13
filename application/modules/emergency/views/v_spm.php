<div class="row gy-5 g-xl-8 mb-xl-8">
    <div class="col-xl-12">
        <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/images/svg/misc/taieri.svg') ?>');">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div>
                        <h1>Laporan Standar Pelayanan Mutu IGD</h1>
                        <p class="mb-0">
                            Monitoring waktu pelayanan IGD dan kepatuhan terhadap standar waktu tunggu rawat inap untuk mendukung peningkatan mutu layanan pasien.
                        </p>
                    </div>
                </div>
                <div class="d-flex overflow-auto min-h-30px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
						<li class="nav-item">
							<a class="nav-link text-muted active" data-bs-toggle="tab" href="#spri">Surat Perintah Rawat Inap</a>
						</li>
                        <li class="nav-item">
							<a class="nav-link text-muted" data-bs-toggle="tab" href="#transferruang">Form Transfer Ruang</a>
						</li>
					</ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content mt-5">
	<div class="tab-pane fade active show" id="spri" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-xl-12">
				<div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
					<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
							<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
						</svg>
					</span>
					<div class="d-flex flex-column pe-0 pe-sm-10">
						<h5 class="mb-1">For Your Information</h5>
						<span>
							Grafik ini menunjukkan <strong>rata-rata response time per bulan</strong> dari 
							<strong>pendaftaran IGD hingga pembuatan Surat Perintah Rawat Inap</strong>. 
							Standar <strong>Service Level Agreement (SLA)</strong> yang ditetapkan adalah 
							<strong>6 Jam (360 Menit)</strong>.
						</span>
					</div>
				</div>
				<div class="card card-flush">
					<div class="card-body p-8">
						<div class="card-rounded-bottom" id="grafikspmspri"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="tab-pane fade" id="transferruang" role="tabpanel">
		<div class="row gy-5 g-xl-8 mb-xl-8">
			<div class="col-xl-12">
				<div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10 fa-fade">
					<span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
							<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
						</svg>
					</span>
					<div class="d-flex flex-column pe-0 pe-sm-10">
						<h5 class="mb-1">For Your Information</h5>
						<span>
							Grafik ini menampilkan <strong>rata-rata response time per bulan</strong> 
							dari proses <strong>registrasi rawat inap hingga pembuatan Form Transfer Ruang</strong>. 
							Standar <strong>Service Level Agreement (SLA)</strong> yang ditetapkan adalah 
							<strong>1 Jam (60 Menit)</strong>.
						</span>
					</div>
				</div>
				<div class="card card-flush">
					<div class="card-body p-8">
						<div class="card-rounded-bottom" id="grafiktransfer"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>