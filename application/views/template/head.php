    
<script>
    var url = '<?php echo base_url(); ?>';
    window.targetHarian = <?= (int) (isset($_SESSION['targetharian']) ? $_SESSION['targetharian'] : 0); ?>;
</script>

<title>Analisa Data</title>
<link rel="icon" type="image/gif" href="<?php echo base_url();?>assets/favicon/favicon.png">
<link rel="apple-touch-icon" type="image/gif" href="<?php echo base_url();?>assets/favicon/favicon.png">
<link rel="stylesheet" type="text/css"  href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<?php
    echo "\t\t<link href='".base_url('assets/vendors/animate.css/animate.min.css')."' rel='stylesheet'>".PHP_EOL;
    echo "\t\t<link href='".base_url('assets/vendors/fontawesome-6.5.1/css/all.min.css')."' rel='stylesheet'>".PHP_EOL;
    echo "\t\t<link href='".base_url('assets/vendors/fullcalendar/fullcalendar.bundle.css')."' rel='stylesheet'>".PHP_EOL;

    $csspathmodules = FCPATH.'assets/css/root/';
    if (is_dir($csspathmodules)) {
        $cssfiles = glob($csspathmodules . '*.css');
        echo '<!-- Load CSS File Pada Folder Root System -->'.PHP_EOL;
        foreach ($cssfiles as $cssfile) {
            $cssfilename = basename($cssfile);
            echo "\t\t<link rel='stylesheet' type='text/css' href='".base_url()."assets/css/root/".$cssfilename."'></link>".PHP_EOL;
        }
    }
?>