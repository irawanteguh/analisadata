<?php
$view = $this->input->get('view');
?>

<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

    <!-- Summary -->
    <li class="breadcrumb-item">
        <?php if (empty($view)): ?>
            <span class="text-dark">Summary</span>
        <?php else: ?>
            <a href="<?= site_url('inpatient/resumemedis'); ?>" class="text-muted text-hover-primary">
                Summary
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>


    <li class="breadcrumb-item">
        <?php if ($view === 'indikatormutu'): ?>
            <span class="text-dark">Indikator Mutu</span>
        <?php else: ?>
            <a href="<?= site_url('inpatient/resumemedis?view=indikatormutu'); ?>" class="text-muted text-hover-primary">
                Indikator Mutu
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>


    <li class="breadcrumb-item">
        <?php if ($view === 'rawdata'): ?>
            <span class="text-dark">Raw Data Resume Medis</span>
        <?php else: ?>
            <a href="<?= site_url('inpatient/resumemedis?view=rawdata'); ?>" class="text-muted text-hover-primary">
                Raw Data Resume Medis
            </a>
        <?php endif; ?>
    </li>

</ul>