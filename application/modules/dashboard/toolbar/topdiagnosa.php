<?php
$view = $this->input->get('view');
?>

<?php if ($view === 'detailrjsmf') : ?>

    <a href="#" id="btnDownloadExcelRJSMF" class="btn btn-sm btn-light-primary me-2">
        <i class="bi bi-file-earmark-spreadsheet"></i> Download Excel
    </a>

<?php elseif ($view === 'detailrismf') : ?>

    <a href="#" id="btnDownloadExcelRISMF" class="btn btn-sm btn-light-primary me-2">
        <i class="bi bi-file-earmark-spreadsheet"></i> Download Excel
    </a>

<?php endif; ?>

<div class="d-flex align-items-center overflow-auto pt-3 pt-md-0">
	<div class="d-flex align-items-center">
		<span class="fs-7 fw-bolder text-gray-700 pe-4 text-nowrap">Periode :</span>
		<select data-control="select2" data-placeholder="Please select" class="form-select form-select-sm form-select-solid select2-hidden-accessible" data-hide-search="true" name="selectperiode" id="selectperiode">
			<?php echo $periode;?>
		</select>
	</div>
</div>