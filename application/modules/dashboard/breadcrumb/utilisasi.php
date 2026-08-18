<?php
    $view = $this->input->get('view');
?>

<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">

    <li class="breadcrumb-item">
        <?php if (empty($view)): ?>
            <span class="text-dark">Alat Kesehatan</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/utilisasi'); ?>" class="text-muted text-hover-primary">
                Alat Kesehatan
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>

    <li class="breadcrumb-item">
        <?php if ($view === 'ruangok'): ?>
            <span class="text-dark">Ruang Operasi</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/utilisasi?view=ruangok'); ?>" class="text-muted text-hover-primary">
                Ruang Operasi
            </a>
        <?php endif; ?>
    </li>

    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>

    <li class="breadcrumb-item">
        <?php if ($view === 'mapping'): ?>
            <span class="text-dark">Mapping Tindakan <> Alat Kesehatan</span>
        <?php else: ?>
            <a href="<?= site_url('dashboard/utilisasi?view=mapping'); ?>" class="text-muted text-hover-primary">
                Mapping Tindakan <> Alat Kesehatan
            </a>
        <?php endif; ?>
    </li>

</ul>