<div class="mb-5" id="date-container">
    <label class="form-label fw-bold">Date :</label>
    <input class="form-control form-control-solid flatpickr-input" placeholder="Pick a start date" name="dateperiode" id="dateperiode" type="text">
</div>
<div class="mb-5">
    <label class="form-label fw-bold">Doctor :</label>
    <select data-control="select2" data-dropdown-parent="#kt_menu_61484bf6e3ff8" class="form-select form-select-solid" name="dokterid" id="dokterid">
        <?php echo $listdoctor;?>
    </select>
</div>
<div class="mb-5">
    <label class="form-label fw-bold">Polyclinic :</label>
    <select data-control="select2" data-dropdown-parent="#kt_menu_61484bf6e3ff8" class="form-select form-select-solid" name="poliid" id="poliid">
        <?php echo $listpoli;?>
    </select>
</div>