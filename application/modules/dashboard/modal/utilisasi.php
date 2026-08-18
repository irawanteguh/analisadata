<div class="modal fade" id="modal_mappingalkes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mapping Tindakan - Alat Kesehatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mapping_layanid" name="mapping_layanid">
                <div class="mb-5">
                    <label class="form-label fw-bold">Nama Pelayanan</label>
                    <input type="text" class="form-control form-control-solid" id="mapping_namapelayanan" name="mapping_namapelayanan" readonly>
                </div>
                <div class="mb-5">
                    <label class="form-label fw-bold required">Alat Kesehatan</label>
                    <select class="form-select form-select-solid" id="mapping_deviceid" name="mapping_deviceid" data-control="select2" data-placeholder="Pilih alat kesehatan">
                        <option value="">Pilih alat kesehatan</option>
                        <?php echo $masterdevice;?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnsimpanmapping"><i class="bi bi-save me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</div>