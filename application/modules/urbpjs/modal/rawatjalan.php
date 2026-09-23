<div class="modal fade" id="modal_upload_txt_eklaim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Upload Data Txt E-Klaim</h1>
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
                        <input type="file" class="form-control" id="filetxteklaim" accept=".txt">
                        <button type="button" class="btn btn-primary" id="btnImportTxtEklaim"><i class="bi bi-upload"></i>Import</button>
                    </div>
                    <div class="form-text">Format file: TXT dengan pemisah TAB (tab-delimited).</div>
                </div>
                <br>
                <div class="row mt-4 mb-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Jumlah Data</div>
                            <div class="fw-bold fs-3 text-primary" id="jmlDataEklaim">0</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Total Nilai INA-CBG</div>
                            <div class="fw-bold fs-3 text-success" id="totalNilaiEklaim">0</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Total Tarif RS</div>
                            <div class="fw-bold fs-3 text-warning" id="totalTarifRSEklaim">0</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted fs-8">Selisih</div>
                            <div class="fw-bold fs-3 text-danger" id="selisihTarifEklaim">0</div>
                        </div>
                    </div>

                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed gy-2 fs-8" id="tablePreviewEklaim">
                        <thead class="align-middle" id="headerPreviewtxtEklaim"></thead>
                        <tbody class="fw-bold text-gray-600" id="resultpreviewtxteklaim"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>