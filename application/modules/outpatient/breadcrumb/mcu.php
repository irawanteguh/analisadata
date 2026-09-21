<?php
    $view = $this->input->get('view');
?>

<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

    <li class="breadcrumb-item">
        <?php if (empty($view)): ?>
            <span class="text-dark">Summary</span>
        <?php else: ?>
            <a href="<?= site_url('outpatient/mcu'); ?>" class="text-muted text-hover-primary">
                Summary
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>

    <li class="breadcrumb-item">
        <?php if ($view === 'mcukaryawan'): ?>
            <span class="text-dark">Laporan MCU Karyawan</span>
        <?php else: ?>
            <a href="<?= site_url('outpatient/mcu?view=mcukaryawan'); ?>" class="text-muted text-hover-primary">
                Laporan MCU Karyawan
            </a>
        <?php endif; ?>
    </li>

</ul>