<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require APPPATH . '/libraries/REST_Controller.php';
    require 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;

    if(!function_exists('color')){
        function color($name = null){
            $colors = [
                'reset'          => "\033[0m",
                'black'          => "\033[30m",
                'red'            => "\033[31m",
                'green'          => "\033[32m",
                'yellow'         => "\033[33m",
                'blue'           => "\033[34m",
                'magenta'        => "\033[35m",
                'cyan'           => "\033[36m",
                'white'          => "\033[37m",
                'gray'           => "\033[90m",
                'light_red'      => "\033[91m",
                'light_green'    => "\033[92m",
                'light_yellow'   => "\033[93m",
                'light_blue'     => "\033[94m",
                'light_magenta'  => "\033[95m",
                'light_cyan'     => "\033[96m",
                'light_white'    => "\033[97m",
            ];

            return $colors[$name] ?? $colors['reset'];
        }
    }

    class RawatInap extends REST_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelwhatsapp","mw");
            $this->load->model("ModelRawatInap","md");
            headerlogwhatsapp();
        }

        public function listpasienrencanapulang_POST(){
            $templateid                    = "e2341fb7-829a-443b-be59-6ce2c30f5cfe";
            $templatename                  = "Pasien Rencana Pulang";
            $resultlistpasienrencanapulang = $this->md->listpasienrencanapulang();

            if(empty($resultlistpasienrencanapulang)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            $tglrencanapulang = date('d-m-Y');

            $listpasien = [
                'T' => [],
                'N' => []
            ];

            foreach ($resultlistpasienrencanapulang as $a) {
                $tutd  = strtoupper(trim($a->TUTD ?? 'N')) == 'T' ? 'T' : 'N';
                $ruang = $a->NAMARUANG;

                $listpasien[$tutd][$ruang][] = $a->MRPASIEN . "\t" . $a->NAMAPASIEN . " [" . $a->RUANGRWT_ID . "]";
            }

            $pesanpasien = [];

            foreach (['T' => '🟢 DISCHARGE PLANNING [TUTD]', 'N' => '🟠 DISCHARGE PLANNING [NON TUTD]'] as $tutd => $judul) {
                $pesanpasien[] = "*" . $judul . "*";
                $pesanpasien[] = "";

                if ($tutd == 'T' && empty($listpasien['T'])) {
                    $pesanpasien[] = "Tidak Ada Pasien TUTD";
                    $pesanpasien[] = "";
                    continue;
                }

                foreach ($listpasien[$tutd] as $ruang => $pasien) {
                    $pesanpasien[] = "*" . $ruang . "*";

                    foreach ($pasien as $i => $nama) {
                        $pesanpasien[] = ($i + 1) . ". " . $nama;
                    }

                    $pesanpasien[] = "";
                }
            }

            $vars = [
                'rencanapulang' => $tglrencanapulang,
                'listpasien'    => implode("\n", $pesanpasien)
            ];

            $phone = [
                '6281288646630',
                '6285249823047',
                '6281326456523',
                '6287820440958',  // Handphone Lantai 4
                '6282310931880',  // Handphone Lantai 5
                '628112094296',   // Lavender Lt. 6 Tim Baru
                '628114191098',   // Handphone Lantai 7 Tim 1
                '6281389745791',  // Lantai 7 (nomor tambahan)
                '628115931195',   // Handphone Lantai 8 Tim 1
                '6281295362291',  // Lantai 8 (nomor tambahan)
                '628111356573',   // Handphone Lantai 9
                '6281389745780',  // Lantai 9 (nomor tambahan)
                '628119469974',   // Lantai 9 (nomor tambahan)
                '628114306652',   // Cempaka Lantai 10 Tim 1
                '6281218422033',  // Handphone Lantai 10 Tim 2
                '6281223321734'   // Handphone Lantai 11
            ];

            foreach ($phone as $number) {
                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_DUTYMANAGER,$number,$templateid,$templatename,$vars);
                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
            
        }

        public function listpasientransit_POST(){
            $templateid                    = "c79803ff-c7ea-4e9d-9df7-1bd889ef9a38";
            $templatename                  = "Pasien Transit";
            $resultlistpasienrencanapulang = $this->md->listpasientransit();

            if (empty($resultlistpasienrencanapulang) || count($resultlistpasienrencanapulang) < 10) {
                echo color('red') . "Data pasien transit kurang dari 10 (" . count($resultlistpasienrencanapulang) . " pasien)";
                return;
            }

            $tglrencanapulang = date('d-m-Y');

            $listpasien = [];

            foreach ($resultlistpasienrencanapulang as $i => $a) {
                $listpasien[] = ($i + 1) . ". " . $a->MRPASIEN . "\t" . $a->NAMAPASIEN .
                                "\n   Kelas: " . $a->KELAS_ID .
                                "\n   Masuk transit: " . $a->TGLMSASUKTRANSIT.
                                "\n   Waktu Tunggu: " . $a->LAMATRANSIT.
                                "\n   DPJP: " . str_replace('.', ' ', $a->DPJP).
                                "\n   Diagnosis: " . $a->DIAGNOSA;               
            }

            $vars = [
                'rencanapulang' => $tglrencanapulang,
                'listpasien'    => $pesanpasien = implode("\n\n", $listpasien)
            ];

            $phone = [
                '6281288646630',
                '6285249823047',
                '6281326456523',
                '6287820440958',  // Handphone Lantai 4
                '6282310931880',  // Handphone Lantai 5
                '628112094296',   // Lavender Lt. 6 Tim Baru
                '628114191098',   // Handphone Lantai 7 Tim 1
                '6281389745791',  // Lantai 7 (nomor tambahan)
                '628115931195',   // Handphone Lantai 8 Tim 1
                '6281295362291',  // Lantai 8 (nomor tambahan)
                '628111356573',   // Handphone Lantai 9
                '6281389745780',  // Lantai 9 (nomor tambahan)
                '628119469974',   // Lantai 9 (nomor tambahan)
                '628114306652',   // Cempaka Lantai 10 Tim 1
                '6281218422033',  // Handphone Lantai 10 Tim 2
                '6281223321734'   // Handphone Lantai 11
            ];

            foreach ($phone as $number) {
                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_DUTYMANAGER,$number,$templateid,$templatename,$vars);
                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
            
        }
    }

?>