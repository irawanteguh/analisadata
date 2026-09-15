<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require APPPATH . '/libraries/REST_Controller.php';
    require 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;

    class Rawatjalan extends REST_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelwhatsapp","mw");
            $this->load->model("Modelbookingrawatjalan","md");
        }

        public function listpasienbooking_POST(){
            $resultlistpasienbooking = $this->md->listpasienbooking();

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $waktu         = $a->JAM_MULAI.' - '.$a->JAM_SELESAI;
                $param_booking = ['e' => $a->EPISODE_ID,'p' => $a->PASIEN_ID];
                $p             = base64_encode(json_encode($param_booking));
                $link_booking  = 'https://rsudpasarminggu.jakarta.go.id/daftaronline/index.php/pendaftaran-poli/bpjs-v2/cetak-struk-booking?p='.$p;

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER),
                    'hari_tanggal' => $a->TGL_MASUK,
                    'waktu'        => $waktu,
                    'kode_booking' => $a->BOOKING_ID.' ('.$a->URUT.')',
                    'link_booking' => $link_booking
                ];

                $result = $this->openwa->sendTemplate(
                    '70ac719d-e2aa-405f-bd50-a1aaa724bfdf',
                    $a->NOMORHP,
                    '4ab79b3e-7cb9-4edf-8586-1c6d288f1feb',
                    'Booking Rawat Jalan',
                    $vars
                );

                if ($result['success']) {
                    $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                    $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                    $datasimpan['NO_HP']         = $a->NOMORHP;
                    $datasimpan['X_ID']          = $result['response']['messageId'];
                    $datasimpan['TIMESTAMP']     = $result['response']['timestamp'];
                    $datasimpan['TEMPLATE_NAME'] = 'Booking Rawat Jalan';

                    $this->mw->insertlogwhatsapp($datasimpan);
                }

                $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                $this->response([
                    'status'  => $result['success'],
                    'message' => $result['message'],
                    'data'    => $vars,
                    'response' => $result['response']
                ], $http_status);
            }
        }

        public function listbatalbooking_POST(){
            $resultlistpasienbooking = $this->md->listbatalbooking();

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            $hasil = [];

            foreach ($resultlistpasienbooking as $a) {
                $waktu = $a->JAM_MULAI . ' - ' . $a->JAM_SELESAI;

                $caption =
                    "Halo *" . $a->NAMAPASIEN . "*,\n\n" .

                    "Kami ingin menginformasikan bahwa janji temu Anda dengan *" .
                    str_replace('.', ',', $a->NAMADOKTER) .
                    "* pada *" . $a->TGL_MASUK .
                    "*, pukul *" . $waktu .
                    "* telah dibatalkan.\n\n" .

                    "Kami memahami bahwa perubahan rencana dapat terjadi. Jika Anda ingin melanjutkan konsultasi dengan dokter, Anda dapat melakukan pendaftaran kembali melalui layanan pendaftaran online kami.\n\n" .
                    "Kami akan dengan senang hati membantu Anda mendapatkan jadwal kunjungan berikutnya.\n\n" .
                    "Sampai jumpa di RSUD Pasar Minggu. Kami siap melayani Anda kembali. 🙏";

                $result = $this->openwa->sendImage(
                    '70ac719d-e2aa-405f-bd50-a1aaa724bfdf',
                    $a->NOMORHP,
                    'https://rsudpasarminggu.jakarta.go.id/analisadata/assets/media/whatsapp/batal_booking.png',
                    $caption
                );

                if ($result['success']) {
                    $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                    $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                    $datasimpan['NO_HP']         = $a->NOMORHP;
                    $datasimpan['X_ID']          = $result['response']['messageId'];
                    $datasimpan['TIMESTAMP']     = $result['response']['timestamp'];
                    $datasimpan['TEMPLATE_NAME'] = 'Batal Rawat Jalan';

                    $this->mw->insertlogwhatsapp($datasimpan);
                }

                $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                $this->response([
                    'status'   => $result['success'],
                    'message'  => $result['message'],
                    'caption'  => $caption,
                    'response' => $result['response']
                ], $http_status);
            }
        }

        public function listreminderbooking_POST(){
            $resultlistpasienbooking = $this->md->listreminderbooking();

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultlistpasienbooking as $a){
                $waktu         = $a->JAM_MULAI.' - '.$a->JAM_SELESAI;
                $param_booking = ['e' => $a->EPISODE_ID,'p' => $a->PASIEN_ID];
                $p             = base64_encode(json_encode($param_booking));
                $link_booking  = 'https://rsudpasarminggu.jakarta.go.id/daftaronline/index.php/pendaftaran-poli/bpjs-v2/cetak-struk-booking?p='.$p;

                $vars = [
                    'nama_pasien'  => $a->NAMAPASIEN,
                    'nama_dokter'  => str_replace('.', ',', $a->NAMADOKTER),
                    'hari_tanggal' => $a->TGL_MASUK,
                    'waktu'        => $waktu,
                    'kode_booking' => $a->BOOKING_ID.' ('.$a->URUT.')',
                    'link_booking' => $link_booking
                ];

                $result = $this->openwa->sendTemplate(
                    '70ac719d-e2aa-405f-bd50-a1aaa724bfdf',
                    $a->NOMORHP,
                    '1b57bcfa-ce88-49ab-b067-a28669cca268',
                    'Reminder Rawat Jalan',
                    $vars
                );

                if ($result['success']) {
                    $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                    $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                    $datasimpan['NO_HP']         = $a->NOMORHP;
                    $datasimpan['X_ID']          = $result['response']['messageId'];
                    $datasimpan['TIMESTAMP']     = $result['response']['timestamp'];
                    $datasimpan['TEMPLATE_NAME'] = 'Reminder Rawat Jalan';

                    $this->mw->insertlogwhatsapp($datasimpan);
                }

                $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                $this->response([
                    'status'  => $result['success'],
                    'message' => $result['message'],
                    'data'    => $vars,
                    'response' => $result['response']
                ], $http_status);
            }
        }

        public function listpasienadaobat_POST(){
            $resultlistpasienobat = $this->md->listpasienadaobat();

            if(empty($resultlistpasienobat)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach ($resultlistpasienobat as $a) {

                $message =
                    "Halo *" . $a->NAMAPASIEN . "*,\n\n" .
                    "Dokter *" . str_replace('.', ',', $a->NAMADOKTER) .
                    "* telah meresepkan obat untuk Anda.\n" .
                    "Agar obat Anda dapat segera diproses, silakan melakukan *check-in farmasi* melalui kiosk yang tersedia di area rumah sakit.\n" .
                    "Setelah check-in, silakan menunggu proses penyiapan obat oleh petugas farmasi.\n\n" .
                    "Terima kasih atas kepercayaan Anda kepada *RSUD Pasar Minggu*.\n" .
                    "Semoga pengobatan berjalan lancar dan Anda segera kembali sehat. 🙏";


                $result = $this->openwa->sendText(
                    '70ac719d-e2aa-405f-bd50-a1aaa724bfdf',
                    $a->NOMORHP,
                    $message
                );

                if ($result['success']) {
                    $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                    $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                    $datasimpan['NO_HP']         = $a->NOMORHP;
                    $datasimpan['X_ID']          = $result['response']['messageId'];
                    $datasimpan['TIMESTAMP']     = $result['response']['timestamp'];
                    $datasimpan['TEMPLATE_NAME'] = 'Pasien Ada Obat Rawat Jalan';

                    $this->mw->insertlogwhatsapp($datasimpan);
                }


                $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                $this->response([
                    'status'   => $result['success'],
                    'message'  => $result['message'],
                    'message'  => $message,
                    'response' => $result['response']
                ], $http_status);
            }

        }

        public function googlereview_POST(){
            $resultlistpasienbooking = $this->md->googlereview();

            if(empty($resultlistpasienbooking)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            $hasil = [];

            foreach ($resultlistpasienbooking as $a) {

                $caption =
                        "Halo *" . $a->NAMAPASIEN . "*,\n\n" .

                        "Terima kasih telah mempercayakan pelayanan kesehatan Anda kepada *RSUD Pasar Minggu*. 🙏\n" .
                        "Kami ingin terus memberikan pelayanan yang lebih baik. Untuk itu, kami sangat menghargai pengalaman dan masukan Anda melalui *Google Review*.\n" .
                        "⭐ Silakan berikan penilaian dan ulasan Anda tentang pelayanan yang telah diterima.\n" .
                        "Masukan dari Anda sangat berarti bagi kami untuk terus meningkatkan kualitas pelayanan *RSUD Pasar Minggu*.\n" .
                        "Silakan klik link berikut ini : https://search.google.com/local/writereview?placeid=ChIJt8qmfhHyaS4RC5qlxCmCMrA"."\n\n" .

                        "Terima kasih atas dukungan dan kepercayaan Anda. 💙\n" .
                        "*Bersama Anda, Menuju Pelayanan yang Lebih Baik.*";

                $result = $this->openwa->sendImage(
                    '70ac719d-e2aa-405f-bd50-a1aaa724bfdf',
                    $a->NOMORHP,
                    'https://rsudpasarminggu.jakarta.go.id/analisadata/assets/media/whatsapp/google_review.png',
                    $caption
                );

                if ($result['success']) {
                    $datasimpan['PASIEN_ID']     = $a->PASIEN_ID;
                    $datasimpan['EPISODE_ID']    = $a->EPISODE_ID;
                    $datasimpan['NO_HP']         = $a->NOMORHP;
                    $datasimpan['X_ID']          = $result['response']['messageId'];
                    $datasimpan['TIMESTAMP']     = $result['response']['timestamp'];
                    $datasimpan['TEMPLATE_NAME'] = 'Google Review';

                    $this->mw->insertlogwhatsapp($datasimpan);
                }

                $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                $this->response([
                    'status'   => $result['success'],
                    'message'  => $result['message'],
                    'caption'  => $caption,
                    'response' => $result['response']
                ], $http_status);
            }
        }
    }

?>