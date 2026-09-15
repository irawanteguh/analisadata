<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require APPPATH . '/libraries/REST_Controller.php';
    require 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;

    class PeoMagnolia extends REST_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelwhatsapp","mw");
            $this->load->model("Modelpeomagnolia","md");
        }

        public function greeting_POST(){
            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){
                
                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isGreeting($message)){

                    $reply =
                    "👋 *Halo, selamat datang di RSUD Pasar Minggu.*\n\n".
                    "Terima kasih telah menghubungi kami.\n".
                    "Saya *PEO (Patient Experience Officer)*, siap membantu Anda mendapatkan informasi dan layanan di RSUD Pasar Minggu.\n".
                    "Apabila terdapat pertanyaan dan kebutuhan informasi lainnya silahkan tulis pertanyaan dikolom chat.\n".
                    "Terima kasih.\n*RSUD Pasar Minggu*";

                    $result = $this->openwa->sendTextChatid(
                        "a60fc13c-9c63-4d47-9380-c104b3c8c467",
                        $chatid,
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $responseopenwa['status']   = $result['success'];
                    $responseopenwa['message']  = $result['message']; 
                    $responseopenwa['response'] = $result['response'];

                    $this->mw->updatewebhook($a->IDEMPOTENCY_KEY, array("RESPONSE_STATUS"=>"Y"));
                    $this->response($responseopenwa, $http_status);
                }
            }

        }

        public function biaya_POST(){

            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isBiaya($message)){

                    $reply =
                    "👩‍⚕️ *Informasi Biaya Pemeriksaan*\n\n".
                    "Untuk biaya pendaftaran dan pemeriksaan dokter sebesar *Rp. 300.000*.\n".
                    "Biaya tersebut *belum termasuk biaya tindakan / treatment dan obat*, apabila diperlukan.\n".
                    "Mohon informasikan jenis tindakan atau treatment yang ingin ditanyakan agar kami dapat membantu memberikan informasi lebih lanjut.";

                    $result = $this->openwa->sendTextChatid(
                        "a60fc13c-9c63-4d47-9380-c104b3c8c467",
                        $chatid,
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $responseopenwa['status']   = $result['success'];
                    $responseopenwa['message']  = $result['message'];
                    $responseopenwa['response'] = $result['response'];

                    $this->mw->updatewebhook($a->IDEMPOTENCY_KEY, array("RESPONSE_STATUS"=>"Y"));
                    $this->response($responseopenwa, $http_status);
                }
            }
        }

        public function pendaftaran_POST(){

            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isPendaftaran($message)){

                    $reply =
                        "📋 *Informasi Pendaftaran RSUD Pasar Minggu*\n\n".
                        "Untuk pendaftaran, Bapak/Ibu dapat mendaftar secara online melalui *e-Pasien*:\n".
                        "https://rsudpasarminggu.jakarta.go.id/e-pasien/index.php/auth\n\n".
                        "Atau dapat langsung mendaftar di *Klinik Magnolia Lantai 2* sesuai dengan jam praktik dokter. 🙏🏻\n\n".
                        "⚠️ *Pendaftaran ditutup 30 menit sebelum jam praktik dokter.*";

                    $result = $this->openwa->sendTextChatid(
                        "a60fc13c-9c63-4d47-9380-c104b3c8c467",
                        $chatid,
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $responseopenwa['status']   = $result['success'];
                    $responseopenwa['message']  = $result['message'];
                    $responseopenwa['response'] = $result['response'];

                    $this->mw->updatewebhook($a->IDEMPOTENCY_KEY, array("RESPONSE_STATUS"=>"Y"));
                    $this->response($responseopenwa, $http_status);
                }
            }
        }

        public function bpjs_POST(){

            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isBPJS($message)){

                    $reply =
                        "🏥 *Informasi Pelayanan BPJS*\n\n".
                        "Untuk pasien peserta *BPJS Kesehatan*, pada umumnya pelayanan rujukan ke rumah sakit memerlukan surat rujukan dari *Fasilitas Kesehatan Tingkat Pertama (FKTP/Faskes 1)* sesuai dengan ketentuan BPJS Kesehatan.\n\n".
                        "Namun, untuk kondisi tertentu seperti *kegawatdaruratan*,  pasien dapat langsung datang ke *IGD RSUD Pasar Minggu* tanpa menggunakan surat rujukan terlebih dahulu. .\n\n".
                        "Silakan memastikan status rujukan dan kepesertaan BPJS terlebih dahulu melalui Faskes 1 atau layanan BPJS Kesehatan.";

                    $result = $this->openwa->sendTextChatid(
                        "a60fc13c-9c63-4d47-9380-c104b3c8c467",
                        $chatid,
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $responseopenwa['status']   = $result['success'];
                    $responseopenwa['message']  = $result['message'];
                    $responseopenwa['response'] = $result['response'];

                    $this->mw->updatewebhook($a->IDEMPOTENCY_KEY, array("RESPONSE_STATUS"=>"Y"));
                    $this->response($responseopenwa, $http_status);
                }
            }
        }

        public function alamat_POST(){
            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isAlamat($message)){

                    $latitude  = -6.285987;
                    $longitude = 106.831987;

                    $description = "RSUD Pasar Minggu\n" .
                                "Jl. TB Simatupang No. 1, Ragunan, " .
                                "Pasar Minggu, Jakarta Selatan";

                    $result = $this->openwa->shareloc(
                        "a60fc13c-9c63-4d47-9380-c104b3c8c467",
                        $chatid,
                        $latitude,
                        $longitude,
                        $description
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $responseopenwa['status']   = $result['success'];
                    $responseopenwa['message']  = $result['message'];
                    $responseopenwa['response'] = $result['response'];

                    $this->mw->updatewebhook($a->IDEMPOTENCY_KEY, array("RESPONSE_STATUS"=>"Y"));
                    $this->response($responseopenwa, $http_status);
                }
            }
        }

        public function keluhan_POST(){
            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isKeluhan($message)){

                    $reply =
                        "Terima kasih telah menyampaikan keluhan Anda.\n\n".
                        "Saya *PEO (Patient Experience Officer)* RSUD Pasar Minggu.\n".
                        "Kami akan membantu meneruskan dan memberikan informasi terkait keluhan yang Anda sampaikan.\n\n".
                        "Mohon sampaikan keluhan atau kendala yang Anda alami secara lengkap agar kami dapat membantu dengan lebih baik.\n\n".
                        "Terima kasih atas kepercayaan Anda kepada *RSUD Pasar Minggu*.";

                    $result = $this->openwa->sendImageChatid(
                        "70ac719d-e2aa-405f-bd50-a1aaa724bfdf",
                        $chatid,
                        "https://rsudpasarminggu.jakarta.go.id/analisadata/assets/media/whatsapp/media_keluhan.png",
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $this->response([
                        'status'   => $result['success'],
                        'message'  => $result['message'],
                        'response' => $result['response']
                    ], $http_status);
                }
            }
        }

        public function jambesuk_POST(){
            $resultdatawebhook = $this->md->datawebhook();

            if(empty($resultdatawebhook)){
                echo color('red')."Data Tidak Ditemukan";
                return;
            }

            foreach($resultdatawebhook as $a){

                $message = isset($a->MESSAGE_BODY) ? trim($a->MESSAGE_BODY->load()) : '';
                $chatid  = isset($a->CHAT_ID) ? trim($a->CHAT_ID) : '';

                if(empty($message) || empty($chatid)){
                    continue;
                }

                if(isJamBesuk($message)){

                    $reply =
                        "👋 *Informasi Jam Besuk RSUD Pasar Minggu*\n\n".
                        "Berikut informasi mengenai jadwal kunjungan atau jam besuk pasien di RSUD Pasar Minggu.\n\n".
                        "Terima kasih.\n*RSUD Pasar Minggu*";

                    $result = $this->openwa->sendImageChatid(
                        "70ac719d-e2aa-405f-bd50-a1aaa724bfdf",
                        $chatid,
                        "https://rsudpasarminggu.jakarta.go.id/analisadata/assets/media/whatsapp/media_jam_besuk.png",
                        $reply
                    );

                    $http_status = $result['success'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_GATEWAY;

                    $this->response([
                        'status'   => $result['success'],
                        'message'  => $result['message'],
                        'response' => $result['response']
                    ], $http_status);
                }
            }
        }

    }

?>