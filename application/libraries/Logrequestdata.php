<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logrequestdata {

    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    public function simpanrequestdata($data){
        if(empty($data)){
            return false;
        }

        $ip = $this->CI->input->ip_address();

        if($ip == '::1' || $ip == '127.0.0.1'){
            return false;
        }

        $durasi = isset($data->durasi) ? (float)$data->durasi : 0;
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
        $waktusekarang = date('Y-m-d H:i:s');
        $tglmulai = date('Y-m-d H:i:s', strtotime($waktusekarang) - ($durasi / 1000));

        $datasimpan['TUJUAN']              = "Permohonan Pengambilan Data : ".$data->jenisdata." Periode : ".$data->periode;
        $datasimpan['KETERANGAN_SELESAI']  = "Permohonan Pengambilan Data : ".$data->jenisdata." Periode : ".$data->periode." Sudah Di Tindaklanjuti";
        $datasimpan['BIDANG_ID']           = 'B04';
        $datasimpan['JENIS_LAPORAN']       = 'J31';
        $datasimpan['FAKTOR_LAPORAN']      = 'F01';
        $datasimpan['STATUS_LAPORAN']      = '01';
        $datasimpan['CREATED_BY']          = 'SIRS01_'.$userid;
        $datasimpan['TGL_TL']              = $tglmulai;
        $datasimpan['TGL_MULAI_LAPORAN']   = $tglmulai;
        $datasimpan['TGL_SELESAI_LAPORAN'] = $waktusekarang;
        $datasimpan['TGL_VALIDASI']        = $waktusekarang;
        $datasimpan['USER_IT']             = mt_rand(0, 1) ? '1521027' : '2511259';
        $datasimpan['CREATED_DATE']        = $tglmulai;

        $sql = "INSERT INTO SR01_ETICKET_MS (
            TUJUAN,
            KETERANGAN_SELESAI,
            BIDANG_ID,
            JENIS_LAPORAN,
            FAKTOR_LAPORAN,
            STATUS_LAPORAN,
            CREATED_BY,
            TGL_TL,
            TGL_MULAI_LAPORAN,
            TGL_SELESAI_LAPORAN,
            TGL_VALIDASI,
            USER_IT,
            CREATED_DATE
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS'),
            TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS'),
            TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS'),
            TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS'),
            ?,
            TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS')
        )";

        return $this->CI->db->query($sql, array(
            $datasimpan['TUJUAN'],
            $datasimpan['KETERANGAN_SELESAI'],
            $datasimpan['BIDANG_ID'],
            $datasimpan['JENIS_LAPORAN'],
            $datasimpan['FAKTOR_LAPORAN'],
            $datasimpan['STATUS_LAPORAN'],
            $datasimpan['CREATED_BY'],
            $datasimpan['TGL_TL'],
            $datasimpan['TGL_MULAI_LAPORAN'],
            $datasimpan['TGL_SELESAI_LAPORAN'],
            $datasimpan['TGL_VALIDASI'],
            $datasimpan['USER_IT'],
            $datasimpan['CREATED_DATE']
        ));
    }
}
?>