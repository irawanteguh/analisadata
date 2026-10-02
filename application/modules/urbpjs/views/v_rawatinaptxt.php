<div class="col-xl-12">
    <div class="row">

        <!-- TOTAL KUNJUNGAN -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-primary bg-light-primary shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hospital-user fs-3 text-primary me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-primary">
                                Total Kunjungan
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                Total Episode BPJS
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-4 text-primary">
                        <span id="jmlkunjungan">0</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- TOTAL TARIF RS -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-info bg-light-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-hospital fs-3 text-info me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-info">
                                Total Tarif RS
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                Total Tarif Rumah Sakit
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-5 text-info">
                        Rp <span id="jmltarifrs">0</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- TOTAL INA-CBG -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-file-invoice-dollar fs-3 text-success me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-success">
                                Total INA-CBG
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                Total Tarif INA-CBG
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-5 text-success">
                        Rp <span id="jmlinacbg">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL IDRG -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-success bg-light-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-project-diagram fs-3 text-success me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-success">
                                Total iDRG
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                Total Tarif iDRG
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-5 text-success">
                        Rp <span id="jmlidrg">0</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- SELISIH RS VS INA-CBG -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-dark bg-light-dark shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-balance-scale fs-3 text-dark me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-dark">
                                Selisih RS vs INA-CBG
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                INA-CBG − Tarif RS
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-5 text-dark">
                        Rp <span id="selisihinacbg">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SELISIH RS VS iDRG -->
        <div class="col-xl-2 col-lg-4 col-md-6 mb-5">
            <div class="card border border-dark bg-light-dark shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-balance-scale fs-3 text-dark me-2"></i>
                        <div>
                            <div class="fw-bold fs-6 text-dark">
                                Selisih RS vs iDRG
                            </div>
                            <div class="text-muted" style="font-size:11px">
                                iDRG − Tarif RS
                            </div>
                        </div>
                    </div>

                    <div class="fw-bolder fs-5 text-dark">
                        Rp <span id="selisihidrg">0</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row col-xl-12 mb-5">
    <div class="col-xl-6">
        <div class="card card-flush">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Tren Kunjungan</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Jumlah kunjungan per bulan</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="card-rounded-bottom" id="trenkunjungan"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card card-flush">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Perbandingan Tarik Inacbg - IDRG</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Jumlah pendapatan per bulan</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="card-rounded-bottom" id="trenpendapatan"></div>
            </div>
        </div>
    </div>
</div>

<div class="row col-xl-12 mb-5">
    <div class="col-xl-9">
        <div class="card card-flush">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Severity Level</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Distribusi tingkat keparahan kasus</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="card-rounded-bottom" id="severitylevelmountly"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card card-flush">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Severity Level</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Distribusi tingkat keparahan kasus</span>
                </h3>
            </div>
            <div class="card-body py-3">
                <div class="card-rounded-bottom" id="severitylevelyear"></div>
            </div>
        </div>
    </div>
</div>
