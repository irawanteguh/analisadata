<div class="row gy-5 g-xl-8 mb-xl-8">
    <div class="col-xl-12">
        <div class="card rounded bgi-no-repeat bgi-position-x-end bgi-size-cover" style="background-color: #ffffff; background-position: calc(100% + 0.5rem) 100%;background-size: 20% auto;background-image: url('<?= base_url('assets/media/svg/misc/eolic-energy.svg') ?>');">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div>
                        <h1>Audit Medik Stoke</h1>
                        <p class="text-muted fs-6">SLA Tindakan CT Scan Pada Pasien Stroke</p>
                    </div>
                </div>

                <div class="d-flex overflow-auto min-h-30px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                        <li class="nav-item"><a class="nav-link text-muted active" data-bs-toggle="tab" href="#januari" data-kode-bulan="01">Januari</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#februari" data-kode-bulan="02">Februari</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#maret" data-kode-bulan="03">Maret</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#april" data-kode-bulan="04">April</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#mei" data-kode-bulan="05">Mei</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#juni" data-kode-bulan="06">Juni</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#juli" data-kode-bulan="07">Juli</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#agustus" data-kode-bulan="08">Agustus</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#september" data-kode-bulan="09">September</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#oktober" data-kode-bulan="10">Oktober</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#november" data-kode-bulan="11">November</a></li>
                        <li class="nav-item"><a class="nav-link text-muted" data-bs-toggle="tab" href="#desember" data-kode-bulan="12">Desember</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content mt-5">
        <?php
            $bulan = [
                "01"=>"Januari",
                "02"=>"Februari",
                "03"=>"Maret",
                "04"=>"April",
                "05"=>"Mei",
                "06"=>"Juni",
                "07"=>"Juli",
                "08"=>"Agustus",
                "09"=>"September",
                "10"=>"Oktober",
                "11"=>"November",
                "12"=>"Desember"
            ];

            foreach($bulan as $kode=>$nama){
                $active = ($kode == "01") ? "show active" : "";
            ?>
            <div class="tab-pane fade <?= $active ?>" id="<?= strtolower($nama) ?>" role="tabpanel">
                <div class="card card-flush">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1"><?= $nama ?></span>
                        </h3>
                        <div class="card-toolbar m-0">
                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-3 svg-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="5" y="5" width="5" height="5" rx="1" fill="#000000"/>
                                            <rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
                                            <rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
                                            <rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg>
                                </span>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">More Actions</div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" onclick="exportTableToExcel('tabledata<?= $kode ?>', 'Laporan Audit Stroke <?= $nama ?>')"> Download Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mh-610px">
                            <table class="table align-middle table-row-dashed fs-8 gy-2" id="tabledata<?= $kode ?>">
                                <thead class="align-middle text-center">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="rounded-start">#</th>
                                        <th>Mr Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tgl Lahir</th>
                                        <th>Umur</th>
                                        <th>Tgl Masuk</th>
                                        <th>Tgl Regis IGD</th>
                                        <th>Tgl Code Stroke</th>
                                        <th>Jml Order CT Scan</th>
                                        <th>First Order CT Scan</th>
                                        <th>Last Order CT Scan</th>
                                        <th>First Radiografer CT Scan</th>
                                        <th>Last Radiografer CT Scan</th>
                                        <th>First Radiolog CT Scan</th>
                                        <th>Last Radiolog CT Scan</th>
                                        <th class="pe-4 text-end rounded-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold" id="resultdatabln<?= $kode ?>" data-kode-bulan="<?= $kode ?>"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>