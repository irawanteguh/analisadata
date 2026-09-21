<div class="col-xl-12 mb-5">
    <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-5 fa-fade">
        <span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
                <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
            </svg>
        </span>
        <div class="d-flex flex-column pe-0 pe-sm-10">
            <h5 class="mb-1">Waktu Tunggu Rawat Jalan</h5>
            <span>
                waktu yang dibutuhkan mulai pasien kontak dengan petugas pendaftaran hingga mendapat pelayanan dari dokter/dokter spesialis
                <ul>
                    <li>Esklusi</li>
                    <ul>
                        <li>Poli Medical Check Up</li>
                        <li>Poli Gigi</li>
                        <li>Haemodialisa</li>
                    </ul>
                </ul>
            </span>
        </div>
    </div>
</div>

<div class="col-xl-12 mb-5">
    <div class="card card-flush">
        <div class="card-header pt-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"></path>
                        </svg>
                    </span>
                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Data" id="searchtable">
                </div>
            </div>
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
                        <a href="#" class="menu-link px-3" id="btndownloaddatawaktutunggu_table">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-2 fs-8" id="datawaktutunggu_table">
                    <thead class="align-middle">
                        <tr class="fw-bolder text-muted bg-light">
                            <th class="ps-4 rounded-start">#</th>
                            <th>MR PASIEN</th>
                            <th>NAMA PASIEN</th>
                            <th>BOOKING ID</th>
                            <th>TGL MASUK</th>
                            <th>POLIKLINIK</th>
                            <th>NAMA DOKTER</th>
                            <th>CHECK-IN</th>
                            <th>MULAI ANAMNESA</th>
                            <th>SELESAI ANAMNESA</th>
                            <th>MULAI PERIKSA</th>
                            <th class="pe-4 rounded-end text-end">WAKTU TUNGGU</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600" id="resultdatawaktutunggu"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>