<?php
    $isDetail = ($this->input->get('view') === 'detail');
?>

<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
    <li class="breadcrumb-item">
        <?php if ($isDetail): ?>
            <a href="<?= site_url('urbpjs/rawatjalan'); ?>" class="text-muted text-hover-primary">
                Summary
            </a>
        <?php else: ?>
            <span class="text-dark">Summary</span>
        <?php endif; ?>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item">
        <?php if ($isDetail): ?>
            <span class="text-dark">Detail</span>
        <?php else: ?>
            <a href="<?= site_url('urbpjs/rawatjalan?view=detail'); ?>" class="text-muted text-hover-primary">
                Detail
            </a>
        <?php endif; ?>
    </li>
</ul>