<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Listrujukan extends CI_Controller {

		public function __construct(){
            parent:: __construct();
			rootsystem::system();
            bpjs::init();
            $this->load->model("Modellistrujukan","md");
        }

		public function index(){
			$this->template->load("template/template-sidebar","v_listrujukan");
		}

        public function listrujukabydate(){
            set_time_limit(300);

            $startInput = $this->input->post("startDate");
            $endInput   = $this->input->post("endDate");

            $start = DateTime::createFromFormat('d-m-Y', $startInput) ?: DateTime::createFromFormat('Y-m-d', $startInput);
            $end   = DateTime::createFromFormat('d-m-Y', $endInput) ?: DateTime::createFromFormat('Y-m-d', $endInput);

            if(!$start || !$end){
                echo json_encode([
                    "responCode" => "99",
                    "responHead" => "error",
                    "responDesc" => "Invalid date format"
                ]);
                return;
            }

            $end->modify('+1 day');

            $period  = new DatePeriod($start,new DateInterval('P1D'),$end);
            $allData = [];

            foreach ($period as $dt) {
                $tanggal = $dt->format("Y-m-d");
                $response = bpjs::listrujukabydate($tanggal, $tanggal);

                if(!isset($response['metaData']['code']) || $response['metaData']['code'] != '200' || empty($response['response']['list'])){
                    continue;
                }

                foreach ($response['response']['list'] as $row) {

                    if (empty($row['noRujukan'])) {
                        continue;
                    }

                    $alreadyExist = $this->md->cekrujukan($row['noRujukan']);
                    $detail       = [];

                    if (!$alreadyExist) {
                        $detailResponse = bpjs::listrujukabynomorrujukan($row['noRujukan']);
                        $detail = isset($detailResponse['response']['rujukan']) ? $detailResponse['response']['rujukan'] : [];
                    }

                    $internal = null;
                    if(!empty($row['noSep'])){
                        $internal = $this->md->dataperujukinternal($row['noSep']);
                    }

                    $tglRujukan = null;
                    if(!empty($row['tglRujukan'])){
                        $date = DateTime::createFromFormat('d-m-Y', $row['tglRujukan']);
                        $tglRujukan = $date ? $date->format('Y-m-d') : null;
                    }

                    $datasimpan = [
                        'NO_KARTU'      => isset($row['noKartu']) ? $row['noKartu'] : null,
                        'NAMA'          => isset($row['nama']) ? $row['nama'] : null,
                        'POLI'          => isset($internal->POLITUJUAN) ? $internal->POLITUJUAN : (isset($internal->RUANGRWT_ID) ? $internal->RUANGRWT_ID : null),
                        'NO_SEP'        => isset($row['noSep']) ? $row['noSep'] : null,
                        'NO_RUJUKAN'    => isset($row['noRujukan']) ? $row['noRujukan'] : null,
                        'POLI_TUJUAN'   => isset($detail['namaPoliRujukan']) ? $detail['namaPoliRujukan'] : null,
                        'ICD_X_CODE'    => isset($detail['diagRujukan']) ? $detail['diagRujukan'] : null,
                        'ICD_X_DESC'    => isset($detail['namaDiagRujukan']) ? $detail['namaDiagRujukan'] : null,
                        'RS_ID_RUJUKAN' => isset($row['ppkDirujuk']) ? $row['ppkDirujuk'] : null,
                        'RS_RUJUKAN'    => isset($row['namaPpkDirujuk']) ? $row['namaPpkDirujuk'] : null,
                        'TGL_RUJUKAN'   => $tglRujukan
                    ];

                    if(!$alreadyExist){
                        $this->md->insertlogrujukan($datasimpan);
                    }

                    $allData[] = $datasimpan;
                }
            }

            $response = [];
            $response['metaData'] = ['code'=>'200','message'=>'OK'];
            $response['response'] = ['list'=>array_values($allData)];

            $json = [
                "responCode"   => "00",
                "responHead"   => "success",
                "responDesc"   => "Data Di Temukan",
                "responResult" => $response
            ];

            echo json_encode($json);
        }

        // public function listrujukabydate() {
        //     $parameter1 = $this->input->post("startDate");
        //     $parameter2 = $this->input->post("endDate");

        //     $response = bpjs::listrujukabydate($parameter1, $parameter2);

        //     if (isset($response['response']['list']) && is_array($response['response']['list'])) {
        //         foreach ($response['response']['list'] as $key => $row) {
        //             if (!empty($row['noSep'])) {
        //                 $internal = $this->md->dataperujukinternal($row['noSep']);

        //                 $response['response']['list'][$key]['poliAsal']      = isset($internal->POLITUJUAN) ? $internal->POLITUJUAN : (isset($internal->RUANGRWT_ID) ? $internal->RUANGRWT_ID : null);
        //                 $response['response']['list'][$key]['dokterPerujuk'] = isset($internal->NAMADOKTER) ? $internal->NAMADOKTER : null;


        //                 $responsedetail = bpjs::listrujukabynomorrujukan($row['noRujukan']);

        //                 $response['response']['list'][$key]['icdxcode']       = $responsedetail['response']['rujukan']['diagRujukan'];
        //                 $response['response']['list'][$key]['icdxdesc']       = $responsedetail['response']['rujukan']['namaDiagRujukan'];
        //                 $response['response']['list'][$key]['catatan']        = $responsedetail['response']['rujukan']['catatan'];
        //                 $response['response']['list'][$key]['kodepolitujuan'] = $responsedetail['response']['rujukan']['poliRujukan'];
        //                 $response['response']['list'][$key]['namapolitujuan'] = $responsedetail['response']['rujukan']['namaPoliRujukan'];
        //             } else {
        //                 $response['response']['list'][$key]['poliAsal']      = null;
        //                 $response['response']['list'][$key]['dokterPerujuk'] = null;
        //             }
        //         }
        //     }

        //     $json["responCode"]   = "00";
        //     $json["responHead"]   = "success";
        //     $json["responDesc"]   = "Data Di Temukan";
        //     $json["responResult"] = $response;

        //     echo json_encode($json);
        // }
	}
?>