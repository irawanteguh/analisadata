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

    class Rawatjalan extends REST_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelwhatsapp","mw");
            $this->load->model("Modelrawatjalan","md");
            headerlogwhatsapp();
        }

        public function listpasienbooking_POST(){
            $templateid   = "598a1d0d-78f2-4b7b-b5be-aafc04adda7b";
            $templatename = "Booking Rawat Jalan";

            $resultlistpasienbooking = $this->md->listpasienbooking($templateid);

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $result       = [];
                $datasimpan   = [];
                $vars         = [];
                $waktu        = $a->JAM_MULAI.' - '.$a->JAM_SELESAI;
                $link_booking = 'https://rsudpasarminggu.jakarta.go.id/daftaronline/index.php/pendaftaran-poli/bpjs-v2/cetak-struk-booking?p='.base64_encode(json_encode(['e'=>$a->EPISODE_ID,'p'=>$a->PASIEN_ID]));

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER),
                    'hari_tanggal' => $a->TGL_MASUK,
                    'waktu'        => $waktu,
                    'kode_booking' => $a->BOOKING_ID.' ('.$a->URUT.')',
                    'link_booking' => $link_booking
                ];

                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_PEO,$a->NOMORHP,$templateid,$templatename,$vars);

                $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                $datasimpan['NO_HP']         = $a->NOMORHP;
                $datasimpan['X_ID']          = $result['response']['messageId'] ?? '';
                $datasimpan['TIMESTAMP']     = $result['response']['timestamp'] ?? '';
                $datasimpan['STATUS']        = $result['success'] ?? '';
                $datasimpan['TEMPLATE_NAME'] = $templatename;
                $datasimpan['TEMPLATE_ID']   = $templateid;
                $this->mw->insertlogwhatsapp($datasimpan);

                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
        }

        public function listreminderbooking_POST(){
            $templateid   = "8d47f983-2b6a-489d-8fa5-37b1e3099c41";
            $templatename = "Reminder Rawat Jalan";

            $resultlistpasienbooking = $this->md->listreminderbooking($templateid);

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $result       = [];
                $datasimpan   = [];
                $vars         = [];
                $waktu        = $a->JAM_MULAI.' - '.$a->JAM_SELESAI;
                $link_booking = 'https://rsudpasarminggu.jakarta.go.id/daftaronline/index.php/pendaftaran-poli/bpjs-v2/cetak-struk-booking?p='.base64_encode(json_encode(['e'=>$a->EPISODE_ID,'p'=>$a->PASIEN_ID]));

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER),
                    'hari_tanggal' => $a->TGL_MASUK,
                    'waktu'        => $waktu,
                    'kode_booking' => $a->BOOKING_ID.' ('.$a->URUT.')',
                    'link_booking' => $link_booking
                ];

                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_PEO,$a->NOMORHP,$templateid,$templatename,$vars);

                $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                $datasimpan['NO_HP']         = $a->NOMORHP;
                $datasimpan['X_ID']          = $result['response']['messageId'] ?? '';
                $datasimpan['TIMESTAMP']     = $result['response']['timestamp'] ?? '';
                $datasimpan['STATUS']        = $result['success'] ?? '';
                $datasimpan['TEMPLATE_NAME'] = $templatename;
                $datasimpan['TEMPLATE_ID']   = $templateid;
                $this->mw->insertlogwhatsapp($datasimpan);

                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
        }

        public function listbatalbooking_POST(){
            $templateid   = "64acfe0a-1d88-445a-b693-cad9a24fc8ab";
            $templatename = "Batal Booking";

            $resultlistpasienbooking = $this->md->listpasienbatalbooking($templateid);

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $result       = [];
                $datasimpan   = [];
                $vars         = [];
                $waktu        = $a->JAM_MULAI.' - '.$a->JAM_SELESAI;

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER),
                    'hari_tanggal' => $a->TGL_MASUK,
                    'waktu'        => $waktu
                ];

                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_PEO,$a->NOMORHP,$templateid,$templatename,$vars);

                $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                $datasimpan['NO_HP']         = $a->NOMORHP;
                $datasimpan['X_ID']          = $result['response']['messageId'] ?? '';
                $datasimpan['TIMESTAMP']     = $result['response']['timestamp'] ?? '';
                $datasimpan['STATUS']        = $result['success'] ?? '';
                $datasimpan['TEMPLATE_NAME'] = $templatename;
                $datasimpan['TEMPLATE_ID']   = $templateid;
                $this->mw->insertlogwhatsapp($datasimpan);

                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
        }

        public function listpasienadaobat_POST(){
            $templateid   = "24910f75-ac57-4b01-93f1-e0fd5ee63d65";
            $templatename = "Ada Resep Obat";

            $resultlistpasienbooking = $this->md->listpasienadaobat($templateid);

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $result       = [];
                $datasimpan   = [];
                $vars         = [];

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER)
                ];

                $result = $this->openwa->sendTemplate(OPENWA_SESSION_ID_PEO,$a->NOMORHP,$templateid,$templatename,$vars);

                $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                $datasimpan['NO_HP']         = $a->NOMORHP;
                $datasimpan['X_ID']          = $result['response']['messageId'] ?? '';
                $datasimpan['TIMESTAMP']     = $result['response']['timestamp'] ?? '';
                $datasimpan['STATUS']        = $result['success'] ?? '';
                $datasimpan['TEMPLATE_NAME'] = $templatename;
                $datasimpan['TEMPLATE_ID']   = $templateid;
                $this->mw->insertlogwhatsapp($datasimpan);

                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
        }

        public function googlereview_POST(){
            $templateid   = "1ef87844-ddbb-46bb-8405-f9a2a280f95d";
            $templatename = "Google Review";
            $linkimage     = 'https://rsudpasarminggu.jakarta.go.id/analisadata/assets/media/whatsapp/google_review.png';

            $resultlistpasienbooking = $this->md->googlereview($templateid);

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach ($resultlistpasienbooking as $a) {

                $caption =
                        "Halo *" . $a->NAMAPASIEN . "*,\n\n" .

                        "Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada *RSUD Pasar Minggu*. 🙏\n" .
                        "Kami ingin terus memberikan pelayanan yang lebih baik. Untuk itu, kami sangat menghargai pengalaman dan masukan Anda melalui *Google Review*.\n" .
                        "⭐ Silakan berikan penilaian dan ulasan Anda tentang pelayanan yang telah diterima.\n" .
                        "Masukan dari Anda sangat berarti bagi kami untuk terus meningkatkan kualitas pelayanan *RSUD Pasar Minggu*.\n" .
                        "Silakan klik link berikut ini : https://share.google/TcMaR25FVOf7BrgaK"."\n\n" .
                        
                        "PEO (Patient Experience Officer)\nAsisten layanan digital RSUD Pasar Minggu";

                $result = $this->openwa->sendImage(OPENWA_SESSION_ID_PEO,$a->NOMORHP,$linkimage,$caption);

                $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                $datasimpan['NO_HP']         = $a->NOMORHP;
                $datasimpan['X_ID']          = $result['response']['messageId'] ?? '';
                $datasimpan['TIMESTAMP']     = $result['response']['timestamp'] ?? '';
                $datasimpan['STATUS']        = $result['success'] ?? '';
                $datasimpan['TEMPLATE_NAME'] = $templatename;
                $datasimpan['TEMPLATE_ID']   = $templateid;
                $this->mw->insertlogwhatsapp($datasimpan);

                echo formatlogwhatsapp(!empty($result['response']['timestamp']) ? date('Y-m-d H:i:s',$result['response']['timestamp']) : date('Y-m-d H:i:s'),$result['response']['messageId'] ?? '',isset($result['response']) ? ($result['success'] ? 'SENT' : 'FAILED') : 'FAILED',isset($result['response']) ? ($result['message'] ?? '') : 'Tidak Mendapatkan response',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red',isset($result['response']) && !empty($result['success']) ? 'green' : 'red');
            }
        }
    }

?>