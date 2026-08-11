<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-primary bg-light-primary shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hospital-user fs-3 text-primary me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-primary">Kunjungan</div>
                            <div class="text-muted" style="font-size:11px">
                                Rawat Jalan BPJS
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-primary">
                        <span id="jmlkunjungan">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-danger bg-light-danger shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-medical-alt fs-3 text-danger me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-danger">SEP Belum Terbuat</div>
                            <div class="text-muted" style="font-size:11px">
                                Total kunjungan BPJS tanpa SEP
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-danger">
                        <span id="jmlsepbelum">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-medical fs-3 text-success me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-success">SEP Terbuat</div>
                            <div class="text-muted" style="font-size:11px">
                                Total SEP BPJS yang telah diterbitkan
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-success">
                        <span id="jmlsep">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-warning bg-light-warning shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle fs-3 text-warning me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-warning">Belum Grouping</div>
                            <div class="text-muted" style="font-size:11px">
                                SEP Belum Terbuat dan Belum Grouping
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-warning">
                        <span id="jmlbelumgrouping">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-layer-group fs-3 text-success me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-success">Grouping INA-CBG</div>
                            <div class="text-muted" style="font-size:11px">
                                Total Kasus Telah Digrouping
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-success">
                        <span id="jmlgrouping">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-danger bg-light-danger shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-wallet fs-3 text-danger me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-danger">Nilai Belum Grouping</div>
                            <div class="text-muted" style="font-size:11px">
                                Total Tarif RS Belum Digrouping
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-danger">
                        <span id="nilaibelumgrouping">Rp 0</span>
                    </div>
                </div>
            </div>
        </div> -->

    </div>
</div>

<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hospital fs-3 text-success me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-success">Total Tarif RS</div>
                            <div class="text-muted" style="font-size:11px">
                                Tarif Rumah Sakit
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-success">
                        <span id="totaltarifrs">0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-info bg-light-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-invoice-dollar fs-3 text-info me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-info">Total INA-CBG</div>
                            <div class="text-muted" style="font-size:11px">
                                Nilai Klaim BPJS
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-info">
                        <span id="totalinacbg">0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-info bg-light-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-ear-listen fs-3 text-info me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-info">ABD</div>
                            <div class="text-muted" style="font-size:11px">
                                Alat Bantu Dengar
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-info">
                        <span id="totalabd">0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-info bg-light-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-pills fs-3 text-info me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-info">Farmasi</div>
                            <div class="text-muted" style="font-size:11px">
                                Kronis
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-info">
                        <span id="totalfarmasi">0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-danger bg-light-danger shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-balance-scale fs-3 text-danger me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-danger">Selisih Tarif</div>
                            <div class="text-muted" style="font-size:11px">
                                Tarif RS - INA-CBG
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-danger">
                        <span id="totalselisih">0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-warning bg-light-warning shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-percentage fs-3 text-warning me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-warning">Coverage</div>
                            <div class="text-muted" style="font-size:11px">
                                INA-CBG - Tarif RS
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder fs-4 text-warning">
                        <span id="coverage">0</span>%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card border border-warning bg-light-warning shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-clock fs-1 text-warning me-3"></i>
                        <div>
                            <div class="fw-bolder fs-4 text-warning">
                                Klaim Pending
                            </div>
                            <div class="text-muted fs-7">
                                Status Klaim BPJS
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Kasus
                            </div>
                            <div class="fw-bolder fs-3 text-warning">
                                <span id="pending_kasus">0</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Nilai
                            </div>
                            <div class="fw-bolder fs-3 text-warning">
                                <span id="pending_nilai">0</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Persen
                            </div>
                            <div class="fw-bolder fs-3 text-warning">
                                <span id="pending_persen">0</span>%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Klaim Layak -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle fs-1 text-success me-3"></i>
                        <div>
                            <div class="fw-bolder fs-4 text-success">
                                Klaim Layak
                            </div>
                            <div class="text-muted fs-7">
                                Status Klaim BPJS
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">

                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Kasus
                            </div>
                            <div class="fw-bolder fs-3 text-success">
                                <span id="layak_kasus">0</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Nilai
                            </div>
                            <div class="fw-bolder fs-3 text-success">
                                <span id="layak_nilai">0</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Persen
                            </div>
                            <div class="fw-bolder fs-3 text-success">
                                <span id="layak_persen">0</span>%
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Klaim Tidak Layak -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card border border-danger bg-light-danger shadow-sm h-100">
                <div class="card-body p-3">

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle fs-1 text-danger me-3"></i>
                        <div>
                            <div class="fw-bolder fs-4 text-danger">
                                Klaim Tidak Layak
                            </div>
                            <div class="text-muted fs-7">
                                Status Klaim BPJS
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">

                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Kasus
                            </div>
                            <div class="fw-bolder fs-3 text-danger">
                                <span id="tidaklayak_kasus">0</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Nilai
                            </div>
                            <div class="fw-bolder fs-3 text-danger">
                                <span id="tidaklayak_nilai">0</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Persen
                            </div>
                            <div class="fw-bolder fs-3 text-danger">
                                <span id="tidaklayak_persen">0</span>%
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Klaim Dispute -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card border border-info bg-light-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-exclamation-circle fs-1 text-info me-3"></i>
                        <div>
                            <div class="fw-bolder fs-4 text-info">
                                Klaim Dispute
                            </div>
                            <div class="text-muted fs-7">
                                Status Klaim BPJS
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Kasus
                            </div>
                            <div class="fw-bolder fs-3 text-info">
                                <span id="dispute_kasus">0</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Nilai
                            </div>
                            <div class="fw-bolder fs-3 text-info">
                                <span id="dispute_nilai">0</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-muted fw-semibold fs-7 mb-1">
                                Persen
                            </div>
                            <div class="fw-bolder fs-3 text-info">
                                <span id="dispute_persen">0</span>%
                            </div>
                        </div>
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
                <span class="card-label fw-bolder fs-3 mb-1">Rekapitulasi Utilization Review Rawat Jalan</span>
                <span class="text-muted mt-1 fw-bold fs-7">Ringkasan bulanan kunjungan, tarif, klaim, dan status hasil review BPJS</span>
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
                        <a href="#" class="menu-link px-3" id="btnDownloadExcelKunjungan">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-2 fs-8" id="dataurrawatjalan_table">
                    <thead class="align-middle">
                        <tr class="fw-bolder">
                            <th class="ps-4 rounded-start bg-dark text-white" rowspan="3">#</th>
                            <th class="text-center bg-dark text-white" rowspan="3">BULAN LAYANAN</th>
                            <th class="text-center bg-dark text-white" rowspan="3">JUMLAH KUNJUNGAN</th>
                            <th class="text-center bg-dark text-white" rowspan="3">TARIF RS</th>
                            <th class="text-center bg-dark text-white" rowspan="3">TARIF INACBG</th>
                            <th class="text-center bg-dark text-white" rowspan="3">ABD</th>
                            <th class="text-center bg-dark text-white" rowspan="3">FRM</th>
                            <th class="text-center bg-dark text-white" rowspan="3">SELISIH</th>
                            <th class="text-center bg-warning" colspan="3">PENDING</th>
                            <th class="text-center bg-info text-white" colspan="6">BAHV</th>
                            <th class="text-center bg-primary text-white" colspan="2">DISPUTE</th>
                            <th class="pe-4 rounded-end text-end bg-dark text-white" rowspan="3">KETERANGAN</th>
                        </tr>
                        <tr class="fw-bolder">
                            <th class="text-center bg-warning" rowspan="2">TOTAL KASUS</th>
                            <th class="text-center bg-warning" rowspan="2">NILAI</th>
                            <th class="text-center bg-warning" rowspan="2">COVERAGE</th>
                            <th class="text-center bg-success text-white" colspan="3">LAYAK</th>
                            <th class="text-center bg-danger text-white" colspan="3">TIDAK LAYAK</th>
                            <th class="text-center bg-primary text-white" rowspan="2">TOTAL KASUS</th>
                            <th class="text-center bg-primary text-white" rowspan="2">NILAI</th>
                            
                        </tr>
                        <tr class="fw-bolder">
                            <th class="text-center bg-success text-white">KASUS</th>
                            <th class="text-center bg-success text-white">NILAI</th>
                            <th class="text-center bg-success text-white">COVERAGE</th>
                            <th class="text-center bg-danger text-white">KASUS</th>
                            <th class="text-center bg-danger text-white">NILAI</th>
                            <th class="text-center bg-danger text-white">COVERAGE</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600" id="resultdataurrjdetail"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-6 mb-5">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Tren Kunjungan Rawat Jalan</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Jumlah kunjungan per bulan</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="card-rounded-bottom" id="trenkunjungan"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-5">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Perbandingan Tarif dan Klaim</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Perbandingan total tarif rumah sakit dengan nilai klaim BPJS</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="card-rounded-bottom" id="perbadingantarif"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-6 mb-5">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Klaim Pending</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Jumlah klaim pending per bulan</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="card-rounded-bottom" id="klaimpending"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-5">
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Klaim Tidak Layak</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Jumlah klaim tidak layak per bulan</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="card-rounded-bottom" id="klaimtidaklayak"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 mb-5">
    <div class="card card-flush">
        <div class="card-header pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Quadrant Performance</span>
                <span class="text-muted mt-1 fw-bold fs-7">Analysis of patient volume and revenue (INA-CBG Revenue vs Hospital Cost)</span>
            </h3>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabdokter">Specialist Doctor</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabsmf">SMF</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabresource">Resource</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="tab-content">
                <div id="tabdokter" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                    <div class="card-rounded-bottom" id="chartquadrant"></div>
                </div>
                <div id="tabsmf" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="card-rounded-bottom" id="chartquadrantsmf"></div>
                </div>
                <div id="tabresource" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="card-rounded-bottom" id="chartquadrantresource"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 mb-5">
    <div class="card card-flush">
        <div class="card-header pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Quadrant Performance Details</span>
                <span class="text-muted mt-1 fw-bold fs-7">Analysis of patient volume and revenue (INA-CBG Revenue vs Hospital Cost)</span>
            </h3>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tabdokterdetails">Specialist Doctor</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tabsmfdetails">SMF</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="tab-content">
                <div id="tabdokterdetails" class="card-body p-0 tab-pane fade show active" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed gy-2 fs-8" id="dataquadrant_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>DOCTOR</th>
                                    <th class="text-center">PATIENT VISITS</th>
                                    <th class="text-end">HOSPITAL CHARGES</th>
                                    <th class="text-end">INA-CBG PAYMENT</th>
                                    <th class="text-end">REVENUE</th>
                                    <th class="text-end">CRR (%)</th>
                                    <th class="pe-4 rounded-end text-end">QUADRANT</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600" id="resultdataquadrant"></tbody>
                        </table>
                    </div>
                </div>
                <div id="tabsmfdetails" class="card-body p-0 tab-pane fade" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed gy-2 fs-8" id="dataquadrantsmf_table">
                            <thead class="align-middle">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>SMF</th>
                                    <th class="text-center">PATIENT VISITS</th>
                                    <th class="text-end">HOSPITAL CHARGES</th>
                                    <th class="text-end">INA-CBG PAYMENT</th>
                                    <th class="text-end">REVENUE</th>
                                    <th class="text-end">CRR (%)</th>
                                    <th class="pe-4 rounded-end text-end">QUADRANT</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600" id="resultdataquadrantsmf"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>