<button id="drawer_dutymanager_toggle" class="explore-toggle btn btn-sm bg-body btn-color-gray-700 btn-active-primary shadow-sm position-fixed px-5 fw-bolder zindex-2 top-50 mt-10 end-0 transform-90 fs-6 rounded-top-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Duty Manager">
    <span id="drawer_dutymanager_toggle_label">Detail Report</span>
</button>

<div id="drawer_dutymanager" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="drawer_dutymanager" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'900px', lg:'900px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#drawer_dutymanager_toggle" data-kt-drawer-close="#drawer_dutymanager_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="drawer_dutymanager_header">
            <h3 class="card-title fw-bolder text-gray-700">
                Summary Report
            </h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="drawer_dutymanager_close">
                    <span class="svg-icon svg-icon-2">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </button>
            </div>
        </div>

        <div class="card-body" id="drawer_dutymanager_body">
            <div id="drawer_dutymanager_scroll" class="scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#drawer_dutymanager_body" data-kt-scroll-dependencies="#drawer_dutymanager_header" data-kt-scroll-offset="5px">
                <div class="mb-5">
                    <ul class="nav nav-stretch nav-line-tabs border-transparent fs-6 fw-bold flex-nowrap">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab1">Informasi Kepala Instalasi Rawat Jalan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab2">Informasi Duty Manager</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content mt-5">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                            <div class="position-relative">
                                <textarea class="form-control" id="kainsrj_txt" rows="30" style="font-family: Consolas, 'Courier New', monospace;" readonly></textarea>
                                <button type="button" class="btn btn-sm btn-primary position-absolute top-0 end-0 m-3" id="btnCopyKainsrj">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                            <div class="position-relative">
                                <textarea class="form-control" id="dutymanager_txt" rows="30" style="font-family: Consolas, 'Courier New', monospace;" readonly></textarea>
                                <button type="button" class="btn btn-sm btn-primary position-absolute top-0 end-0 m-3" id="btnCopyDutyManager">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>