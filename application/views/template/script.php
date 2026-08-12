<script src="<?= base_url('assets/routingsystem/global/plugins.bundle.js') ?>"></script>
<script src="<?= base_url('assets/routingsystem/custom/datatables/datatables.bundle.js') ?>"></script>
<script src="<?= base_url('assets/routingsystem/scripts.bundle.js') ?>"></script>
<script src="<?= base_url('assets/plugins/table2excel/jquery.table2excel.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<?php
    $jspathroot = FCPATH.'assets/js/root/';
    if (is_dir($jspathroot)) {
        $jsFiles = glob($jspathroot . '*.js');
        echo PHP_EOL.'<!-- Load Js Root System -->'.PHP_EOL;
        foreach ($jsFiles as $jsFile) {
            $jsFilename = basename($jsFile);
            echo "\t\t<script type='text/javascript' src='".base_url('assets/js/root/'.$jsFilename)."'></script>".PHP_EOL;
        }
    };

    if(file_exists(FCPATH . "assets/js/" . $this->uri->segment(1) . "/" . $this->uri->segment(2) . ".js")){
        echo PHP_EOL . '<!-- Load JS Files Folder ' . $this->uri->segment(1) . '/' . $this->uri->segment(2) . ' -->' . PHP_EOL;
        echo "\t\t<script type='text/javascript' src='" . base_url('assets/js/' . $this->uri->segment(1) . "/" . $this->uri->segment(2) . ".js?v=" . time()) . "'></script>" . PHP_EOL;
    };
?>

<script>
    const globalnamauser = <?= json_encode(isset($_SESSION['name']) ? $_SESSION['name'] : '') ?>;
</script>