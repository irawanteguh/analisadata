<div class="d-flex align-items-center overflow-auto pt-3 pt-md-0 me-2">
	<div class="d-flex align-items-center">
		<span class="fs-7 fw-bolder text-gray-700 pe-4 text-nowrap">Periode :</span>
		<select data-control="select2" data-placeholder="Please select" class="form-select form-select-sm form-select-solid select2-hidden-accessible" data-hide-search="true" name="selectperiode" id="selectperiode">
			<?php echo $periode;?>
		</select>
	</div>
</div>

<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-arrow-repeat"></i> Synchronize Data</button>
    <ul class="dropdown-menu">
        <!-- <li>
            <a class="dropdown-item btn btn-sm" href="#" onclick="syncdata_txtcoding();"><i class="bi bi-file-earmark-text"></i> File TXT - Coding</a>
        </li> -->
        <li>
            <a class="dropdown-item btn btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#modal_upload_eklaim"><i class="bi bi-file-earmark-check"></i> Upload E-KLAIM</a>
		</li>
        <li>
            <a class="dropdown-item btn btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#modal_upload_bahv"><i class="bi bi-file-earmark-check"></i> Upload BAHV</a>
		</li>
        <!-- <li>
            <a class="dropdown-item btn btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#modal_upload_farmasi"><i class="bi bi-capsule"></i> Upload Farmasi</a>
        </li> -->
    </ul>
</div>