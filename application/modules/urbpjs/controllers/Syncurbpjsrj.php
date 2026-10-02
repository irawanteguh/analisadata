<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Syncurbpjsrj extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			$this->load->model("Modelsyncurbpjsrj","md");
        }

		// public function importbahv(){
		// 	$data = json_decode($this->input->post('data'), true);

		// 	if(empty($data)){
		// 		echo json_encode([
		// 			"status"  => false,
		// 			"message" => "Data kosong."
		// 		]);
		// 		return;
		// 	}

		// 	foreach ($data as $row) {
		// 		$bahv = strtoupper(trim($row["BAHV"]));
				
		// 		if ($bahv == "LAYAK") {
		// 			$bahv = "Y";
		// 		} elseif ($bahv == "TIDAK LAYAK") {
		// 			$bahv = "T";
		// 		} elseif ($bahv == "Y") {
		// 			$bahv = "Y";
		// 		} elseif ($bahv == "T") {
		// 			$bahv = "T";
		// 		} else {
		// 			continue;
		// 		}

		// 		$cekdatasep = $this->md->cekdatasep($row["NO_SEP"]);

		// 		$dataupdate['PASIEN_ID']     = isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null;
		// 		$dataupdate['EPISODE_ID']    = isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null;
		// 		$dataupdate['TGL_MASUK']     = isset($cekdatasep->TGLKUNJUNGAN) ? $cekdatasep->TGLKUNJUNGAN : null;
		// 		$dataupdate['JENIS_EPISODE'] = isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null;
		// 		$dataupdate['NO_SEP']        = $row["NO_SEP"];
		// 		$dataupdate['BAHV']          = $bahv;
		// 		$dataupdate['CREATED_BY']    = 'SIRS01_'.$_SESSION['userid'];

		// 		$resultcekdatastatusur = $this->md->cekdatastatusur($row["NO_SEP"]);
		// 		if(empty($resultcekdatastatusur)){
		// 			$this->md->insertstatusur($dataupdate);
		// 		}else{
		// 			$this->md->updatestatusur($row["NO_SEP"],$dataupdate);
		// 		}

		// 		$datacodingbahv['BAHV'] = $bahv;
		// 		$this->md->updatecoding(isset($cekdatasep->PASIEN_ID) ? $cekdatasep->PASIEN_ID : null, isset($cekdatasep->EPISODE_ID) ? $cekdatasep->EPISODE_ID : null, $row["NO_SEP"], $datacodingbahv);
		// 	}

		// 	echo json_encode(["status"  => true,"message" => "BAHV berhasil diupdate."]);
		// }

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

		// public function importeklaim(){
		// 	$rows = json_decode($this->input->post('data'), true);

		// 	if (!$rows) {
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
		// 			"TARIF_INACBG"  => $row["NILAI_INACBG"],
		// 			"JENIS_EPISODE" => isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null,
		// 			"JENIS"         => "1",
		// 			"AKTIF"         => "1",
		// 			"CREATED_BY"    => "SIRS01_" . $_SESSION["userid"]
		// 		];

		// 		$resultcekdataeklaim = $this->md->cekdataeklaim(isset($cekdatasep->SEP_JENISLAYAN) ? ($cekdatasep->SEP_JENISLAYAN == '2' ? 'O' : 'I') : null,$row["NO_SEP"]);
		// 		if(empty($resultcekdataeklaim)){
		// 			$this->md->inserturbpjs($data);
		// 		}else{
		// 			$this->md->updateurbpjs($row["NO_SEP"], $data);
		// 		}

		// 		if (!empty($cekdatasep->PASIEN_ID) && !empty($cekdatasep->EPISODE_ID)) {
		// 			$datacoding = [
		// 				'CODING_ID'     => 'IMP_'.$cekdatasep->EPISODE_ID,
		// 				'PASIEN_ID'     => $cekdatasep->PASIEN_ID,
		// 				'EPISODE_ID'    => $cekdatasep->EPISODE_ID,
		// 				'NOMOR_KARTU'   => $cekdatasep->NOKARTU,
		// 				'NOMOR_SEP'     => $row["NO_SEP"],
		// 				"TARIF_INACBG"  => $row["NILAI_INACBG"],
		// 				'KELAS_RAWAT'   => '3',
		// 				'JENIS_RAWAT'   => $cekdatasep->SEP_JENISLAYAN,
		// 				'NOMOR_RM'      => $cekdatasep->MRPAS,
		// 				'NAMA_PASIEN'   => $cekdatasep->NAMAPASIEN,
		// 				'AKTIF'         => '1',
		// 				'CODING_SOURCE' => 'GROUPING'
		// 			];

		// 			$resultcekdatacoding = $this->md->cekdatacoding($cekdatasep->PASIEN_ID,$cekdatasep->EPISODE_ID);
		// 			if (empty($resultcekdatacoding)) {
		// 				$this->md->insertcoding($datacoding);
		// 			} else {
		// 				$this->md->updatecoding($cekdatasep->PASIEN_ID, $cekdatasep->EPISODE_ID, $row["NO_SEP"], $datacoding);
		// 			}
		// 		}
		// 	}
		// 	echo json_encode(["status" => true]);
		// }

		// public function importtxteklaim(){
		// 	$headers = json_decode($this->input->post('headers'), true);
		// 	$rows = json_decode($this->input->post('data'), true);

		// 	if (!$headers || !is_array($headers) || !$rows || !is_array($rows)) {
		// 		echo json_encode([
		// 			"responCode" => "01",
		// 			"responMsg" => "Data atau header kosong.",
		// 			"responResult" => []
		// 		]);
		// 		return;
		// 	}

		// 	$success = 0;
		// 	$failed = 0;

		// 	foreach ($rows as $row) {
		// 		if (!is_array($row)) {
		// 			$failed++;
		// 			continue;
		// 		}

		// 		if (count($row) < count($headers)) {
		// 			$row = array_pad($row, count($headers), "");
		// 		} elseif (count($row) > count($headers)) {
		// 			$row = array_slice($row, 0, count($headers));
		// 		}

		// 		$row = array_combine($headers, $row);

		// 		if ($row === false) {
		// 			$failed++;
		// 			continue;
		// 		}

		// 		$no_sep = isset($row["SEP"]) ? trim((string)$row["SEP"]) : "";

		// 		if ($no_sep == "") {
		// 			$failed++;
		// 			continue;
		// 		}

		// 		$cekdatasep = $this->md->cekdatasep($no_sep);

		// 		if (!empty($cekdatasep->PASIEN_ID) && !empty($cekdatasep->EPISODE_ID)) {

		// 			$datacoding = [
		// 				'CODING_ID'     => 'IMP_' . $cekdatasep->EPISODE_ID,
		// 				'PASIEN_ID'     => $cekdatasep->PASIEN_ID,
		// 				'EPISODE_ID'    => $cekdatasep->EPISODE_ID,
		// 				'NOMOR_KARTU'   => !empty($row["NOKARTU"]) ? $row["NOKARTU"] : $cekdatasep->NOKARTU,
		// 				'NOMOR_SEP'     => $no_sep,
		// 				'TARIF_INACBG'  => isset($row["TARIF_INACBG"]) ? $row["TARIF_INACBG"] : 0,
		// 				'KELAS_RAWAT'   => isset($row["KELAS_RAWAT"]) ? $row["KELAS_RAWAT"] : '3',
		// 				'JENIS_RAWAT'   => $cekdatasep->SEP_JENISLAYAN,
		// 				'NOMOR_RM'      => !empty($row["MRN"]) ? $row["MRN"] : $cekdatasep->MRPAS,
		// 				'NAMA_PASIEN'   => !empty($row["NAMA_PASIEN"]) ? $row["NAMA_PASIEN"] : $cekdatasep->NAMAPASIEN,
		// 				'AKTIF'         => '1',
		// 				'CODING_SOURCE' => 'GROUPING',
		// 				'CREATED_BY'    => $_SESSION['userid']
		// 			];

		// 			$resultcekdatacoding = $this->md->cekdatacoding($cekdatasep->PASIEN_ID, $cekdatasep->EPISODE_ID);

		// 			if (empty($resultcekdatacoding)) {
		// 				$this->md->insertcoding($datacoding);
		// 			} else {
		// 				$resultcekdatacodingimporttxt = $this->md->cekdatacodingimporttxt($cekdatasep->PASIEN_ID, $cekdatasep->EPISODE_ID);
		// 				if(!empty($resultcekdatacodingimporttxt)){
		// 					$this->md->updatecoding($resultcekdatacodingimporttxt->CODING_ID, $cekdatasep->PASIEN_ID, $cekdatasep->EPISODE_ID, $no_sep, $datacoding);
		// 				}
		// 			}

		// 			$success++;
		// 		} else {
		// 			$failed++;
		// 		}
		// 	}

		// 	echo json_encode([
		// 		"responCode" => "00",
		// 		"responMsg" => "Import berhasil.",
		// 		"responResult" => [
		// 			"total" => count($rows),
		// 			"success" => $success,
		// 			"failed" => $failed
		// 		]
		// 	]);
		// }


		public function importtxteklaim(){
			$headers = json_decode($this->input->post('headers'), true);
			$rows    = json_decode($this->input->post('data'), true);

			if (!is_array($headers) || empty($headers) || !is_array($rows)) {
				echo json_encode([
					"responCode"   => "01",
					"responMsg"    => "Data atau header kosong.",
					"responResult" => [
						"total"   => 0,
						"success" => 0,
						"failed"  => 0
					]
				]);
				return;
			}

			$success = 0;
			$failed  = 0;

			foreach ($rows as $row) {
				$sep = "";

				try {

					if (!is_array($row)) {
						$failed++;
						continue;
					}

					if (count($row) < count($headers)) {
						$row = array_pad($row,count($headers),"");
					} elseif (count($row) > count($headers)) {
						$row = array_slice($row,0,count($headers));
					}

					$row = array_combine($headers, $row);
					if ($row === false) {
						$failed++;
						continue;
					}

					$sep = isset($row["SEP"]) ? trim((string)$row["SEP"]) : "";

					if ($sep === "") {
						$failed++;
						continue;
					}


					$data = [];

					$data["KODE_RS"]                = $this->cleanTxt($row["KODE_RS"]);
					$data["KELAS_RS"]               = $this->cleanTxt($row["KELAS_RS"]);
					$data["KELAS_RAWAT"]            = $this->cleanTxt($row["KELAS_RAWAT"]);
					$data["KODE_TARIF"]             = $this->cleanTxt($row["KODE_TARIF"]);
					$data["PTD"]                    = $this->cleanTxt($row["PTD"]);
					$data["ADMISSION_DATE"]         = $this->formatDateEklaim($row["ADMISSION_DATE"]);
					$data["DISCHARGE_DATE"]         = $this->formatDateEklaim($row["DISCHARGE_DATE"]);
					$data["BIRTH_DATE"]             = $this->formatDateEklaim($row["BIRTH_DATE"]);
					$data["BIRTH_WEIGHT"]           = $this->cleanNumber($row["BIRTH_WEIGHT"]);
					$data["SEX"]                    = $this->cleanTxt($row["SEX"]);
					$data["DISCHARGE_STATUS"]       = $this->cleanTxt($row["DISCHARGE_STATUS"]);
					$data["DIAGLIST"]               = $this->cleanTxt($row["DIAGLIST"]);
					$data["PROCLIST"]               = $this->cleanTxt($row["PROCLIST"]);
					$data["ADL1"]                   = $this->cleanTxt($row["ADL1"]);
					$data["ADL2"]                   = $this->cleanTxt($row["ADL2"]);
					$data["IN_SP"]                  = $this->cleanTxt($row["IN_SP"]);
					$data["IN_SR"]                  = $this->cleanTxt($row["IN_SR"]);
					$data["IN_SI"]                  = $this->cleanTxt($row["IN_SI"]);
					$data["IN_SD"]                  = $this->cleanTxt($row["IN_SD"]);
					$data["INACBG"]                 = $this->cleanTxt($row["INACBG"]);
					$data["CMG"]                    = !empty($data["INACBG"]) ? substr($data["INACBG"], 0, 1) : null;
					$data["CG"]                     = !empty($data["INACBG"]) ? substr($data["INACBG"], 2, 1) : null;
					$data["CT"]                     = !empty($data["INACBG"]) ? substr($data["INACBG"], 4, 2) : null;
					$data["SL"]                     = !empty($data["INACBG"]) ? substr($data["INACBG"], 7, 3) : null;
					$data["SUBACUTE"]               = $this->cleanTxt($row["SUBACUTE"]);
					$data["CHRONIC"]                = $this->cleanTxt($row["CHRONIC"]);
					$data["SP"]                     = $this->cleanTxt($row["SP"]);
					$data["SR"]                     = $this->cleanTxt($row["SR"]);
					$data["SI"]                     = $this->cleanTxt($row["SI"]);
					$data["SD"]                     = $this->cleanTxt($row["SD"]);
					$data["DESKRIPSI_INACBG"]       = $this->cleanTxt($row["DESKRIPSI_INACBG"]);
					$data["TARIF_INACBG"]           = $this->cleanNumber($row["TARIF_INACBG"]);
					$data["TARIF_SUBACUTE"]         = $this->cleanNumber($row["TARIF_SUBACUTE"]);
					$data["TARIF_CHRONIC"]          = $this->cleanNumber($row["TARIF_CHRONIC"]);
					$data["DESKRIPSI_SP"]           = $this->cleanTxt($row["DESKRIPSI_SP"]);
					$data["TARIF_SP"]               = $this->cleanNumber($row["TARIF_SP"]);
					$data["DESKRIPSI_SR"]           = $this->cleanTxt($row["DESKRIPSI_SR"]);
					$data["TARIF_SR"]               = $this->cleanNumber($row["TARIF_SR"]);
					$data["DESKRIPSI_SI"]           = $this->cleanTxt($row["DESKRIPSI_SI"]);
					$data["TARIF_SI"]               = $this->cleanNumber($row["TARIF_SI"]);
					$data["DESKRIPSI_SD"]           = $this->cleanTxt($row["DESKRIPSI_SD"]);
					$data["TARIF_SD"]               = $this->cleanNumber($row["TARIF_SD"]);
					$data["TOTAL_TARIF"]            = $this->cleanNumber($row["TOTAL_TARIF"]);
					$data["TARIF_RS"]               = $this->cleanNumber($row["TARIF_RS"]);
					$data["TARIF_POLI_EKS"]         = $this->cleanNumber($row["TARIF_POLI_EKS"]);
					$data["LOS"]                    = $this->cleanNumber($row["LOS"]);
					$data["ICU_INDIKATOR"]          = $this->cleanTxt($row["ICU_INDIKATOR"]);
					$data["ICU_LOS"]                = $this->cleanNumber($row["ICU_LOS"]);
					$data["VENT_HOUR"]              = $this->cleanNumber($row["VENT_HOUR"]);
					$data["NAMA_PASIEN"]            = $this->cleanTxt($row["NAMA_PASIEN"]);
					$data["MRN"]                    = $this->cleanTxt($row["MRN"]);
					$data["UMUR_TAHUN"]             = $this->cleanNumber($row["UMUR_TAHUN"]);
					$data["UMUR_HARI"]              = $this->cleanNumber($row["UMUR_HARI"]);
					$data["DPJP"]                   = $this->cleanTxt($row["DPJP"]);
					$data["SEP"]                    = $this->cleanTxt($row["SEP"]);
					$data["NOKARTU"]                = $this->cleanTxt($row["NOKARTU"]);
					$data["PAYOR_ID"]               = $this->cleanTxt($row["PAYOR_ID"]);
					$data["CODER_ID"]               = $this->cleanTxt($row["CODER_ID"]);
					$data["VERSI_INACBG"]           = $this->cleanTxt($row["VERSI_INACBG"]);
					$data["VERSI_GROUPER"]          = $this->cleanTxt($row["VERSI_GROUPER"]);
					$data["C1"]                     = $this->cleanTxt($row["C1"]);
					$data["C2"]                     = $this->cleanTxt($row["C2"]);
					$data["C3"]                     = $this->cleanTxt($row["C3"]);
					$data["C4"]                     = $this->cleanTxt($row["C4"]);
					$data["PROSEDUR_NON_BEDAH"]     = $this->cleanNumber($row["PROSEDUR_NON_BEDAH"]);
					$data["PROSEDUR_BEDAH"]         = $this->cleanNumber($row["PROSEDUR_BEDAH"]);
					$data["KONSULTASI"]             = $this->cleanNumber($row["KONSULTASI"]);
					$data["TENAGA_AHLI"]            = $this->cleanNumber($row["TENAGA_AHLI"]);
					$data["KEPERAWATAN"]            = $this->cleanNumber($row["KEPERAWATAN"]);
					$data["PENUNJANG"]              = $this->cleanNumber($row["PENUNJANG"]);
					$data["RADIOLOGI"]              = $this->cleanNumber($row["RADIOLOGI"]);
					$data["LABORATORIUM"]           = $this->cleanNumber($row["LABORATORIUM"]);
					$data["PELAYANAN_DARAH"]        = $this->cleanNumber($row["PELAYANAN_DARAH"]);
					$data["REHABILITASI"]           = $this->cleanNumber($row["REHABILITASI"]);
					$data["KAMAR_AKOMODASI"]        = $this->cleanNumber($row["KAMAR_AKOMODASI"]);
					$data["RAWAT_INTENSIF"]         = $this->cleanNumber($row["RAWAT_INTENSIF"]);
					$data["OBAT"]                   = $this->cleanNumber($row["OBAT"]);
					$data["ALKES"]                  = $this->cleanNumber($row["ALKES"]);
					$data["BMHP"]                   = $this->cleanNumber($row["BMHP"]);
					$data["SEWA_ALAT"]              = $this->cleanNumber($row["SEWA_ALAT"]);
					$data["OBAT_KRONIS"]            = $this->cleanNumber($row["OBAT_KRONIS"]);
					$data["OBAT_KEMO"]              = $this->cleanNumber($row["OBAT_KEMO"]);
					$data["IDRG_DIAG_LISTS"]        = $this->cleanTxt($row["IDRG_DIAG_LISTS"]);
					$data["IDRG_PROC_LISTS"]        = $this->cleanTxt($row["IDRG_PROC_LISTS"]);
					$data["IDRG_MDC_NUMBER"]        = $this->cleanTxt($row["IDRG_MDC_NUMBER"]);
					$data["IDRG_MDC_DESCRIPTION"]   = $this->cleanTxt($row["IDRG_MDC_DESCRIPTION"]);
					$data["IDRG_DRG_CODE"]          = $this->cleanTxt($row["IDRG_DRG_CODE"]);
					$data["IDRG_DRG_DESCRIPTION"]   = $this->cleanTxt($row["IDRG_DRG_DESCRIPTION"]);
					$data["DC"]                     = !empty($data["IDRG_DRG_CODE"]) ? substr($data["IDRG_DRG_CODE"], 0, 5) : null;
					$data["PARTITION"]              = !empty($data["IDRG_DRG_CODE"]) ? substr($data["IDRG_DRG_CODE"], 2, 2) : null;
					$data["SPLIT"]                  = !empty($data["IDRG_DRG_CODE"]) ? substr($data["IDRG_DRG_CODE"], 4, 1) : null;
					$data["CL"]                     = !empty($data["IDRG_DRG_CODE"]) ? substr($data["IDRG_DRG_CODE"], -1) : null;
					$data["IDRG_COST_WEIGHT"]       = $this->cleanNumber($row["IDRG_COST_WEIGHT"]);
					$data["IDRG_SA_COST_WEIGHT"]    = $this->cleanNumber($row["IDRG_SA_COST_WEIGHT"]);
					$data["IDRG_CH_COST_WEIGHT"]    = $this->cleanNumber($row["IDRG_CH_COST_WEIGHT"]);
					$data["IDRG_TOP_UP"]            = $this->cleanNumber($row["IDRG_TOP_UP"]);
					$data["IDRG_TOTAL_COST_WEIGHT"] = $this->cleanNumber($row["IDRG_TOTAL_COST_WEIGHT"]);
					$data["IDRG_NBR"]               = $this->cleanNumber($row["IDRG_NBR"]);
					$data["IDRG_TOTAL_TARIF"]       = $this->cleanNumber($row["IDRG_TOTAL_TARIF"]);
					$data["IDRG_GROUPER_VERSION"]   = $this->cleanTxt($row["IDRG_GROUPER_VERSION"]);
					$data["IDRG_LOGIC_VERSION"]     = $this->cleanTxt($row["IDRG_LOGIC_VERSION"]);
					$data["CREATED_BY"]             = isset($_SESSION["userid"]) ? $_SESSION["userid"] : "SYSTEM";

					$cekData = $this->md->cekdatastatusur($sep);

					if (empty($cekData)) {
						$result = $this->md->insertstatusur($data);
					} else {
						$result = $this->md->updatestatusur($sep,$data);
					}

					if ($result === false) {
						$failed++;
						log_message("error","IMPORT E-KLAIM GAGAL SEP " . $sep);
						continue;
					}

					$success++;

				} catch (Exception $e) {
					$failed++;
					log_message("error","IMPORT E-KLAIM SEP ".$sep ." : " .$e->getMessage());
				}
			}

			echo json_encode([
				"responCode"   => "00",
				"responMsg"    => "Import E-Klaim berhasil diproses.",
				"responResult" => [
					"total"   => count($rows),
					"success" => $success,
					"failed"  => $failed
				]
			]);
		}

		private function formatDateEklaim($date){
			if (empty($date) || $date === "-" || strtolower(trim($date)) === "none") {
				return null;
			}

			$date = trim($date);

			$formats = array(
				'd/m/Y',
				'Y-m-d',
				'd-m-Y',
				'm/d/Y'
			);

			foreach ($formats as $format) {
				$dt = DateTime::createFromFormat($format, $date);

				if ($dt !== false && $dt->format($format) === $date) {
					return $dt->format('d-M-Y');
				}
			}

			return null;
		}

		private function cleanTxt($value){
			if ($value === null) {
				return null;
			}

			$value = trim($value);

			if ($value === '' || strtolower($value) === 'none' || $value === '-') {
				return null;
			}

			return trim($value, '"');
		}

		private function cleanNumber($value){
			$value = $this->cleanTxt($value);

			if ($value === null || !is_numeric($value)) {
				return null;
			}

			return $value;
		}

	}
?>