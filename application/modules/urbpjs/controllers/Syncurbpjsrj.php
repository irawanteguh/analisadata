<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Syncurbpjsrj extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			$this->load->model("Modelsyncurbpjsrj","md");
        }

		public function importbahv(){
			$data = json_decode($this->input->post('data'), true);

			if(empty($data)){
				echo json_encode([
					"status"  => false,
					"message" => "Data kosong."
				]);
				return;
			}

			foreach ($data as $row) {
				$bahv = strtoupper(trim($row["BAHV"]));
				
				if ($bahv == "LAYAK") {
					$bahv = "Y";
				} elseif ($bahv == "TIDAK LAYAK") {
					$bahv = "T";
				} elseif ($bahv == "Y") {
					$bahv = "Y";
				} elseif ($bahv == "T") {
					$bahv = "T";
				} else {
					continue;
				}

				$cekdatasep = $this->md->cekdatasep($row["NO_SEP"]);

				$dataupdate['PASIEN_ID']     = isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null;
				$dataupdate['EPISODE_ID']    = isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null;
				$dataupdate['TGL_MASUK']     = isset($cekdatasep->TGLKUNJUNGAN) ? $cekdatasep->TGLKUNJUNGAN : null;
				$dataupdate['JENIS_EPISODE'] = isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null;
				$dataupdate['NO_SEP']        = $row["NO_SEP"];
				$dataupdate['BAHV']          = $bahv;
				$dataupdate['CREATED_BY']    = 'SIRS01_'.$_SESSION['userid'];

				$resultcekdatastatusur = $this->md->cekdatastatusur($row["NO_SEP"]);
				if(empty($resultcekdatastatusur)){
					$this->md->insertstatusur($dataupdate);
				}else{
					$this->md->updatestatusur($row["NO_SEP"],$dataupdate);
				}

				$datacodingbahv['BAHV'] = $bahv;
				$this->md->updatecoding(isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null, isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null, $row["NO_SEP"], $datacodingbahv);
			}

			echo json_encode(["status"  => true,"message" => "BAHV berhasil diupdate."]);
		}

		// public function importfarmasi(){
		// 	$rows = json_decode($this->input->post('data'), true);

		// 	if(!$rows){
		// 		echo json_encode([
		// 			"status" => false,
		// 			"message" => "Data kosong."
		// 		]);

		// 		return;
		// 	}

		// 	foreach ($rows as $row) {
		// 		$cekdatasep = $this->md->cekdatasep($row["NO_SEP"]);

		// 		$data = [
		// 			"PASIEN_ID"     => isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null,
		// 			"EPISODE_ID"    => isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null,
		// 			"TGL_MASUK"     => isset($cekdatasep->TGLKUNJUNGAN) ? $cekdatasep->TGLKUNJUNGAN : null,
		// 			"NO_SEP"        => $row["NO_SEP"],
		// 			"TARIF_FARMASI" => $row["BIAYA_DISETUJUI"],
		// 			"JENIS"         => "2",
		// 			"CREATED_BY"    => 'SIRS01_'.$_SESSION['userid']
		// 		];

		// 		$this->md->inserturbpjs($data);
		// 	}

		// 	echo json_encode(["status" => true]);
		// }

		public function importeklaim(){
			$rows = json_decode($this->input->post('data'), true);

			if (!$rows) {
				echo json_encode([
					"status" => false,
					"message" => "Data kosong."
				]);
				return;
			}

			foreach ($rows as $row) {
				$cekdatasep = $this->md->cekdatasep($row["NO_SEP"]);
				$data = [
					"PASIEN_ID"     => isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null,
					"EPISODE_ID"    => isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null,
					"TGL_MASUK"     => isset($cekdatasep->TGLKUNJUNGAN) ? $cekdatasep->TGLKUNJUNGAN : null,
					"NO_SEP"        => $row["NO_SEP"],
					"TARIF_INACBG"  => $row["NILAI_INACBG"],
					"JENIS_EPISODE" => isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null,
					"JENIS"         => "1",
					"AKTIF"         => "1",
					"CREATED_BY"    => "SIRS01_" . $_SESSION["userid"]
				];


				$resultcekdataeklaim = $this->md->cekdataeklaim(isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null,$row["NO_SEP"]);
				if(empty($resultcekdataeklaim)){
					$this->md->inserturbpjs($data);
				}else{
					$this->md->updateurbpjs($row["NO_SEP"], $data);
				}

				if (!empty($cekdatasep->PASIEN_ID) && !empty($cekdatasep->EPISODE_ID)) {
					$datacoding = [
						'CODING_ID'     => 'IMP_'.$cekdatasep->EPISODE_ID,
						'PASIEN_ID'     => $cekdatasep->PASIEN_ID,
						'EPISODE_ID'    => $cekdatasep->EPISODE_ID,
						'NOMOR_KARTU'   => $cekdatasep->NOKARTU,
						'NOMOR_SEP'     => $row["NO_SEP"],
						"TARIF_INACBG"  => $row["NILAI_INACBG"],
						'KELAS_RAWAT'   => '3',
						'JENIS_RAWAT'   => $cekdatasep->SEP_JENISLAYAN,
						'NOMOR_RM'      => $cekdatasep->MRPAS,
						'NAMA_PASIEN'   => $cekdatasep->NAMAPASIEN,
						'AKTIF'         => '1',
						'CODING_SOURCE' => 'GROUPING'
					];

					$resultcekdatacoding = $this->md->cekdatacoding($cekdatasep->PASIEN_ID,$cekdatasep->EPISODE_ID);
					if (empty($resultcekdatacoding)) {
						$this->md->insertcoding($datacoding);
					} else {
						$this->md->updatecoding($cekdatasep->PASIEN_ID, $cekdatasep->EPISODE_ID, $row["NO_SEP"], $datacoding);
					}
				}
			}
			echo json_encode(["status" => true]);
		}
	}
?>