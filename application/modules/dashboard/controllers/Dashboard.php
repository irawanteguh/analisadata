<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
    require 'vendor/autoload.php';
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';

	class Dashboard extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modeldashboard","md");
        }

		public function index(){
            $data = $this->loadcombobox();
			$this->template->load("template/template-sidebar","v_dashboard",$data);
		}

        public function loadcombobox(){
			$resultperiode = $this->md->periode();

			$periode="";
            foreach($resultperiode as $a ){
                $periode.="<option value='".$a->PERIODE."'>".$a->PERIODE."</option>";
            }

			$data['periode'] = $periode;
            return $data;
		}

        public function pasientransit(){
            $result    = $this->md->pasientransit();
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

		// public function pasienmeninggal(){
        //     $startdate = $this->input->post("startdate");
        //     $endate    = $this->input->post("endate");
        //     $result  = $this->md->pasienmeninggal($startdate,$endate);
            
		// 	if(!empty($result)){
		// 		$json["responCode"]   = "00";
		// 		$json["responHead"]   = "success";
		// 		$json["responDesc"]   = "Data Di Temukan";
		// 		$json['responResult'] = $result;
        //     }else{
        //         $json["responCode"] = "01";
        //         $json["responHead"] = "info";
        //         $json["responDesc"] = "Data Tidak Di Temukan";
        //     }

        //     echo json_encode($json);
        // }

        public function demografiumur(){
            $result    = $this->md->demografiumur();
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }


        public function datakunjunganrj(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganrj($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datakunjunganri(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganri($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datakunjunganigd(){
            $periode = $this->input->post("selectperiode");
            $result  = $this->md->datakunjunganigd($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datakunjunganigdprovider(){
            $periode = $this->input->post("selectperiode");
            $result    = $this->md->datakunjunganigdprovider($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datakunjunganrjprovider(){
            $periode = $this->input->post("selectperiode");
            $result    = $this->md->datakunjunganrjprovider($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function datakunjunganriprovider(){
            $periode = $this->input->post("selectperiode");
            $result    = $this->md->datakunjunganriprovider($periode);
            
			if(!empty($result)){
				$json["responCode"]   = "00";
				$json["responHead"]   = "success";
				$json["responDesc"]   = "Data Di Temukan";
				$json['responResult'] = $result;
            }else{
                $json["responCode"] = "01";
                $json["responHead"] = "info";
                $json["responDesc"] = "Data Tidak Di Temukan";
            }

            echo json_encode($json);
        }

        public function analisaaikunjunganigd(){
            $periode  = $this->input->post("selectperiode");
            $result   = $this->md->datakunjunganigd($periode);
            $datatext = "";

            // array mapping bulan angka -> nama bulan
            $bulan_nama = [
                "01"=>"Januari", "02"=>"Februari", "03"=>"Maret",
                "04"=>"April", "05"=>"Mei", "06"=>"Juni",
                "07"=>"Juli", "08"=>"Agustus", "09"=>"September",
                "10"=>"Oktober", "11"=>"November", "12"=>"Desember"
            ];

            if(empty($result)){
                $datatext = "Tidak dicatat";
            }else{
                $rows = [];
                foreach($result as $a){
                    $bulan = isset($bulan_nama[$a->BULAN]) ? $bulan_nama[$a->BULAN] : $a->BULAN;
                    $rows[] = $bulan." : ".$a->TOTAL_KUNJUNGAN." Kunjungan";
                }

                $datatext = empty($rows) ? "Tidak dicatat" : "- " . implode("\n- ", $rows);
            }

            $prompt = "
                Anda adalah analis data rumah sakit.

                Tugas Anda adalah membuat analisa kunjungan Instalasi Gawat Darurat (IGD) berdasarkan data yang diberikan.

                Aturan:
                1. Gunakan hanya data yang tersedia.
                2. Jangan membuat angka baru.
                3. Hitung total kunjungan dan rata-rata kunjungan per bulan.
                4. Tentukan bulan dengan kunjungan tertinggi dan terendah.
                5. Jelaskan tren kunjungan selama tahun tersebut.
                6. Berikan interpretasi yang bermanfaat untuk manajemen rumah sakit.

                Buat laporan dengan struktur berikut:
                1. Pendahuluan
                2. Statistik Dasar (total dan rata-rata kunjungan)
                3. Bulan dengan Kunjungan Tertinggi
                4. Bulan dengan Kunjungan Terendah
                5. Analisa Tren Kunjungan
                6. Rekomendasi Manajemen
                7. Kesimpulan

                Gunakan bahasa Indonesia formal.

                Data kunjungan IGD:

                {$datatext}
            ";

            $payload = [
                "model" => "llama3:8b",
                "prompt" => $prompt,
                "stream" => false,
                "options" => [
                    "temperature" => 0,
                    "top_p" => 0.9,
                    "repeat_penalty" => 1.1
                ]
            ];

            // endpoint via SSH Tunnel
            $ch = curl_init("http://127.0.0.1:11434/api/generate");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // timeout 30 detik

            $response = curl_exec($ch);

            if(curl_errno($ch)){
                $error = [
                    'status' => false,
                    'message' => 'Gagal menghubungi AI Server: ' . curl_error($ch)
                ];

                curl_close($ch);
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($error));
                return;
            }

            curl_close($ch);

            $result = json_decode($response, true);

            if(!$result || !isset($result['response']) || empty($result['response'])){
                $error = [
                    'status' => false,
                    'message' => 'Response AI tidak valid atau kosong'
                ];

                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode($error));
                return;
            }

            $success = [
                'status' => true,
                'result_ai' => trim($result['response'])
            ];

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($success));
        }
        
	}
?>