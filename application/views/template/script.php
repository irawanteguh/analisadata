


<?php
    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/amcharts_4.10.38/core.js') . "'></script>" . PHP_EOL;
    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/amcharts_4.10.38/charts.js') . "'></script>" . PHP_EOL;
    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/amcharts_4.10.38/themes/animated.js') . "'></script>" . PHP_EOL;
    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/amcharts_4.10.38/plugins/wordCloud.js') . "'></script>" . PHP_EOL;
    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/amcharts_4.10.38/plugins/forceDirected.js') . "'></script>" . PHP_EOL;

    $jspathroot = FCPATH . 'assets/js/core/';

    if (is_dir($jspathroot)) {
        echo PHP_EOL . '<!-- Load Js Core System -->' . PHP_EOL;
        foreach (glob($jspathroot . '*.js') as $jsFile) {
            if (basename($jsFile) !== 'ebitda.js')
                echo "\t\t<script src='" . base_url('assets/js/core/' . basename($jsFile)) . "'></script>" . PHP_EOL;
        }
    }

    echo "\t\t<script type='text/javascript' src='" . base_url('assets/vendors/table2excel/jquery.table2excel.min.js') . "'></script>" . PHP_EOL;

    if (file_exists(FCPATH . 'assets/js/' . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '.js')) {
        echo PHP_EOL . '<!-- Load JS Files Folder ' . $this->uri->segment(1) . '/' . $this->uri->segment(2) . ' -->' . PHP_EOL;
        echo "\t\t<script src='" . base_url('assets/js/' . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '.js?v=' . time()) . "'></script>" . PHP_EOL;
    }

    if ($this->uri->segment(1) === 'ebitda') {
        echo PHP_EOL . '<!-- Load ebitda.js (Last) -->' . PHP_EOL;
        echo "\t\t<script src='" . base_url('assets/js/core/ebitda.js') . "'></script>" . PHP_EOL;
    }

?>