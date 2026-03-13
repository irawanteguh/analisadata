<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rencanapendapatan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            $this->load->model("Modelrencanapendapatan","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_rencanapendapatan.php");
		}

        public function datarencanapendapatan(){
            $result = $this->md->datarencanapendapatan($_SESSION['unitid']);
            
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

		public function daftarlayanan(){
            $result = $this->md->daftarlayanan($_SESSION['unitid']);
            
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

		public function addplan(){
			$layanid = $this->input->post('layanid', true);
			$qty     = $this->input->post('qty', true);
			$kelasid = $this->input->post('kelas_id', true);
			$harga   = $this->input->post('harga', true);

			$userid  = 'SIRS01_' . $_SESSION['userid'];
			$unitid  = $_SESSION['unitid'];

			// ===== CEK DATA SEKALI SAJA =====
			$cek = $this->md->cekdataplan($unitid, $layanid);

			// ===== DATA UMUM =====
			$data = [
				'PPC_ID'       => $unitid,
				'LAYAN_ID'     => $layanid,
				'QTY'          => $qty,
				'HARGA_SATUAN' => $harga,
				'KELAS_ID'     => $kelasid,
				'AKTIF'        => '1'
			];

			// ===== INSERT =====
			if (empty($cek)) {
				$data['PLAN_ID']    = generateuuid();
				$data['CREATED_BY']= $userid;

				$exec = $this->md->insertplan($data);

				if ($exec) {
					$json = [
						'responCode' => '00',
						'responHead' => 'success',
						'responDesc' => 'Data berhasil ditambahkan'
					];
				} else {
					$json = [
						'responCode' => '01',
						'responHead' => 'error',
						'responDesc' => 'Gagal menambahkan data'
					];
				}

			} 
			// ===== UPDATE =====
			else {
				unset($data['PPC_ID'], $data['LAYAN_ID']); // opsional, biar aman

				$exec = $this->md->updateplan($data, $cek->PLAN_ID);

				if ($exec) {
					$json = [
						'responCode' => '00',
						'responHead' => 'success',
						'responDesc' => 'Data berhasil diperbarui'
					];
				} else {
					$json = [
						'responCode' => '01',
						'responHead' => 'error',
						'responDesc' => 'Gagal memperbarui data'
					];
				}
			}

			echo json_encode($json);
		}

        
	}
?>