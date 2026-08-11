<div class="modal fade" id="modal_detailshift" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Detail Perhitungan Shift</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-8 gy-2">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light align-middle">
                                    <th class="ps-4 rounded-start">#</th>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Shift</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Real Masuk</th>
                                    <th>Real Pulang</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end pe-4 rounded-end">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-600 fw-bold" id="resultdetailperhitunganshift"></tbody>

                            <tfoot>
                                <tr class="fw-bolder bg-light">
                                    <td colspan="8" class="text-end ps-4">Total Nominal :</td>
                                    <td class="text-end" id="totalnominaldetail">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>