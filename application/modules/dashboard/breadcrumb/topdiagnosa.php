<?php
$view = $this->input->get('view');
?>

<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

    <!-- Summary -->
    <li class="breadcrumb-item">
        <?php if (empty($view)): ?>
            <span class="text-dark">Summary</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/topdiagnosa'); ?>" class="text-muted text-hover-primary">
                Summary
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>

    <!-- Detail RJ SMF -->
    <li class="breadcrumb-item">
        <?php if ($view === 'detailrjsmf'): ?>
            <span class="text-dark">Detail Rawat Jalan SMF</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/topdiagnosa?view=detailrjsmf'); ?>" class="text-muted text-hover-primary">
                Detail Rawat Jalan SMF
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>

    <!-- Detail RI SMF -->
    <li class="breadcrumb-item">
        <?php if ($view === 'detailrismf'): ?>
            <span class="text-dark">Detail Rawat Inap SMF</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/topdiagnosa?view=detailrismf'); ?>" class="text-muted text-hover-primary">
                Detail Rawat Inap SMF
            </a>
        <?php endif; ?>
    </li>

</ul>