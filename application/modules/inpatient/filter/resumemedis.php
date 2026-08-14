<?php
	$view = $this->input->get('view');
?>

<?php if ($view == 'rawdata') : ?>

    <div class="mb-5" id="date-container">
        <label class="form-label fw-bold">Date :</label>
        <input class="form-control form-control-solid flatpickr-input" name="dateperiode" placeholder="Pick a start date" id="dateperiode" type="text">
    </div>

<?php endif; ?>

