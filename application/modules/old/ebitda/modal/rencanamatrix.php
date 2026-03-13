<div class="modal fade" id="modal_addmatrix" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Tambah Matrix</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </div>
            </div>
            <form action="<?php echo base_url();?>index.php/ebitda/rencanamatrix/addcomponent" id="formaddcomponent">
                <input type="hidden" id="modal_addmatrix_matrixid" name="modal_addmatrix_matrixid">
                <input type="hidden" id="modal_addmatrix_type" name="modal_addmatrix_type">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="col-xl-12 mb-5">
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2 required">Component :</label>
                            <input type="text" class="form-control form-control-solid" id="modal_addmatrix_component" name="modal_addmatrix_component" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">	
                    <input class="btn btn-light-primary" id="modal_addmatrix_btn" type="submit" value="SUBMIT" name="simpan">			
                </div>
            </form>
        </div>
    </div>
</div>