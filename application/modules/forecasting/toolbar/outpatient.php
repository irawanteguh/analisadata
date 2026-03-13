<div class="d-flex align-items-center overflow-auto pt-3 pt-md-0 me-4">
	<div class="d-flex align-items-center">
		<span class="fs-7 fw-bolder text-gray-700 pe-4 text-nowrap">Periode :</span>
		<select data-control="select2" data-placeholder="Please select" class="form-select form-select-sm form-select-solid select2-hidden-accessible" data-hide-search="true" name="selectperiode" id="selectperiode">
			<?php echo $periode;?>
		</select>
	</div>
</div>

<a href="javascript:void(0)" onclick="simulationforecasting()" class="btn btn-primary btn-sm"><i class="bi bi-graph-up-arrow"></i> Simulation Forecasting</a>