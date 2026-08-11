<div class="modal fade" id="modal_upload_eklaim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Upload Data E-Klaim</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <label class="form-label fw-bold">File Excel</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="fileeklaim" accept=".xlsx,.xls">
                        <button type="button" class="btn btn-primary" id="btnImportEklaim"><i class="bi bi-upload"></i>Import</button>
                    </div>
                </div>
                <br>
                <div class="row mt-4 mb-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Jumlah Data</div>
                            <div class="fw-bold fs-3 text-primary" id="jmlDataEklaim">0</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Total Nilai Inacbg</div>
                            <div class="fw-bold fs-3 text-success" id="totalNilaiEklaim">0</div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed gy-2 fs-8" id="tablePreviewEklaim">
                        <thead class="align-middle">
                            <tr class="fw-bolder">
                                <th class="ps-4 rounded-start bg-dark text-white">#</th>
                                <th class="bg-dark text-white">NO. SEP</th>
                                <th class="pe-4 rounded-end text-end bg-dark text-white">NILAI INACBG</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="resultprevieweklaim"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modal_upload_farmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Upload Data Klaim Farmasi</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <label class="form-label fw-bold">File Excel</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="filefarmasi" accept=".xlsx,.xls">
                        <button type="button" class="btn btn-primary" id="btnImportFarmasi"><i class="bi bi-upload"></i>Import</button>
                    </div>
                </div>
                <br>
                <div class="row mt-4 mb-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Jumlah Data</div>
                            <div class="fw-bold fs-3 text-primary" id="jmlDataFarmasi">0</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Total Nilai Disetujui</div>
                            <div class="fw-bold fs-3 text-success" id="totalNilaiFarmasi">0</div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed gy-2 fs-8" id="tablePreviewFarmasi">
                        <thead class="align-middle">
                            <tr class="fw-bolder">
                                <th class="ps-4 rounded-start bg-dark text-white">#</th>
                                <th class="bg-dark text-white">NO. SEP</th>
                                <th class="pe-4 rounded-end text-end bg-dark text-white">NILAI DISETUJUI</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="resultpreviewfarmasi"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="modal_upload_bahv" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Upload Data Hasil BAHV</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <label class="form-label fw-bold">File Excel</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="filebahv" accept=".xlsx,.xls">
                        <button type="button" class="btn btn-primary" id="btnImportBahv"><i class="bi bi-upload"></i> Import</button>
                    </div>
                </div>
                <br>
                <div class="row mt-4 mb-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Jumlah Data</div>
                            <div class="fw-bold fs-3 text-primary" id="jmlDataBahv">0</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Layak</div>
                            <div class="fw-bold fs-3 text-success" id="totalLayakBahv">0</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Tidak Layak</div>
                            <div class="fw-bold fs-3 text-danger" id="totalTidakLayakBahv">0</div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed gy-2 fs-8" id="tablePreviewBahv">
                        <thead class="align-middle">
                            <tr class="fw-bolder">
                                <th class="ps-4 rounded-start bg-dark text-white">#</th>
                                <th class="bg-dark text-white">NO. SEP</th>
                                <th class="pe-4 rounded-end text-end bg-dark text-white">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="resultpreviewbahv"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>