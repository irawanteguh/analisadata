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

    class Rujukankeluar extends REST_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelrujukankeluar","md");
            bpjs::init();
            headerlog();
        }

        public function rujukanbpjs_GET(){
            $resultperiode = $this->md->periode();

            if(empty($resultperiode)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultperiode as $a){
                $response    = [];
                $statusColor = "";
                $statusMsg   = "";

                $response = bpjs::listrujukabydate($a->PERIODE, $a->PERIODE);

                if (isset($response['metaData']) && $response['metaData']['code'] === "201") {
                    $statusColor = "red";
                    $statusMsg   = $response['metaData']['message'];

                    $datasimpan['PERIODE']=$a->PERIODE;
                    $this->md->insertlog($datasimpan);

                    echo formatlog($a->PERIODE,'','','',$statusMsg,'cyan','cyan','cyan','cyan',$statusColor);
                    continue;
                }

                if (isset($response['metaData']) && $response['metaData']['code'] === "200") {
                    

                    foreach ($response['response']['list'] as $row) {
                        $alreadyExist = $this->md->cekrujukan($row['noRujukan']);

                        if(empty($alreadyExist)){
                            $detailResponse = [];
                            $detailResponse = bpjs::listrujukabynomorrujukan($row['noRujukan']);

                            if(isset($detailResponse['metaData']) && $detailResponse['metaData']['code'] === "200"){
                                $datadetail = [];
                                $datadetail = isset($detailResponse['response']['rujukan']) ? $detailResponse['response']['rujukan'] : [];

                                $datasimpandetail = [
                                    'NO_RUJUKAN'            => $datadetail['noRujukan'] ?? null,
                                    'NO_SEP'                => $datadetail['noSep'] ?? null,
                                    'NO_KARTU'              => $datadetail['noKartu'] ?? null,
                                    'NAMA'                  => $datadetail['nama'] ?? null,

                                    'KELAS_RAWAT'           => $datadetail['kelasRawat'] ?? null,
                                    'KELAMIN'               => $datadetail['kelamin'] ?? null,

                                    'TGL_LAHIR'             => $datadetail['tglLahir'] ?? null,
                                    'TGL_SEP'               => $datadetail['tglSep'] ?? null,
                                    'TGL_RUJUKAN'           => $datadetail['tglRujukan'] ?? null,
                                    'TGL_RENCANA_KUNJUNGAN' => $datadetail['tglRencanaKunjungan'] ?? null,

                                    'PPK_DIRUJUK'           => $datadetail['ppkDirujuk'] ?? null,
                                    'NAMA_PPK_DIRUJUK'      => $datadetail['namaPpkDirujuk'] ?? null,

                                    'JNS_PELAYANAN'         => $datadetail['jnsPelayanan'] ?? null,
                                    'CATATAN'               => $datadetail['catatan'] ?? null,

                                    'DIAG_RUJUKAN'          => $datadetail['diagRujukan'] ?? null,
                                    'NAMA_DIAG_RUJUKAN'     => $datadetail['namaDiagRujukan'] ?? null,

                                    'TIPE_RUJUKAN'          => $datadetail['tipeRujukan'] ?? null,
                                    'NAMA_TIPE_RUJUKAN'     => $datadetail['namaTipeRujukan'] ?? null,

                                    'POLI_RUJUKAN'          => $datadetail['poliRujukan'] ?? null,
                                    'NAMA_POLI_RUJUKAN'     => $datadetail['namaPoliRujukan'] ?? null
                                ];

                                $this->md->insertlogrujukan($datasimpandetail);

                                $statusColor = "green";
                                $statusMsg   = $detailResponse['metaData']['message'];

                                echo formatlog($a->PERIODE,$datadetail['noKartu'],$datadetail['noSep'],$datadetail['noRujukan'],$statusMsg,'cyan','cyan','cyan','cyan',$statusColor);
                                continue;
                            }
                        }

                        
                    }

                    $datasimpan['PERIODE']=$a->PERIODE;
                    $this->md->insertlog($datasimpan);
                }
            }

        }
        
    }

?>