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
                        <a href="#" class="menu-link px-3" id="btndownloaddataauditmedik_table">Download Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-2 fs-8" id="dataauditmedik_table">
                    <thead class="align-middle">
                        <tr class="fw-bolder text-muted bg-light">
                            <th class="rounded-start ps-4">#</th>
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
                            <th class="pe-4 text-end rounded-end">Last Radiolog CT Scan</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600" id="resultdataauditmedik"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>