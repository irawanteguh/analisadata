<div class="modal fade" id="modal-detailsrb" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h1 class="mb-3">Detail Surat Rujuk Balik</h1>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <i class="bi bi-x-lg"></i>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="text-start mb-5">
                    <div class="text-muted fw-bold fs-5">Pencarian data PRB (Rujuk Balik) Berdasarkan Nomor SRB</div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>No Kartu</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-nokartu" name="modal-detailsrb-nokartu" readonly>
                    </div>    
                    <div class="col-md-4 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Nama Pasien</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-namapasien" name="modal-detailsrb-namapasien" readonly>
                    </div>  
                    <div class="col-md-4 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Alamat</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-alamat" name="modal-detailsrb-alamat" readonly>
                    </div>  
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>No Telp</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-notlp" name="modal-detailsrb-notlp" readonly>
                    </div> 
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>No SRB</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-nosrb" name="modal-detailsrb-nosrb" readonly>
                    </div>        
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>No SEP</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-nosep" name="modal-detailsrb-nosep" readonly>
                    </div>    
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Tanggal SRB</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-tglsrb" name="modal-detailsrb-tglsrb" readonly>
                    </div>  
                    <div class="col-md-3 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Program</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-program" name="modal-detailsrb-program" readonly>
                    </div>  
                    <div class="col-md-3 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>DPJP</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-dpjp" name="modal-detailsrb-dpjp" readonly>
                    </div>
                    <div class="col-md-2 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Kode Faskes Asal</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-kodefaskes" name="modal-detailsrb-kodefaskes" readonly>
                    </div> 
                    <div class="col-md-10 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Nama Faskes Asal</span>
                        </label>
                        <input type="text" class="form-control form-control-solid form-control-sm" id="modal-detailsrb-namafaskes" name="modal-detailsrb-namafaskes" readonly>
                    </div> 
                    <div class="col-md-6 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Keterangan</span>
                        </label>
                        <textarea class="form-control form-control-solid" name="modal-detailsrb-keterangan" id="modal-detailsrb-keterangan" readonly></textarea>
                    </div>       
                    <div class="col-md-6 mb-5">
                        <label class="d-flex align-items-center fs-5 mb-2">
                            <span>Saran</span>
                        </label>
                        <textarea class="form-control form-control-solid" name="modal-detailsrb-saran" id="modal-detailsrb-saran" readonly></textarea>
                    </div> 
                    <div class="col-md-12 mt-10">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-8 gy-2">
                                <thead class="align-middle">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="ps-4 rounded-start">#</th>
                                        <th>Nama Obat</th>
                                        <th>Signa 1</th>
                                        <th>Signa 2</th>
                                        <th class="pe-4 text-end rounded-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-bold" id="resultdaftarobat"></tbody>
                            </table>
                        </div>
                    </div>         
                </div>
            </div>   
        </div>
    </div>
</div>