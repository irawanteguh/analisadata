<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require 'vendor/autoload.php';

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

class ResumeAI extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("ModelResumeAI","md");
    }

    public function generateresumeai_post($episodeid){
        $body        = [];
        $sourcedata  = [];
        $dataresume  = [];
        $statusjenis = "";

        $resultkunjungan = $this->md->kunjungan($episodeid);
        $statusjenis     = $resultkunjungan->STATUSJENIS;
        $dokterid        = $resultkunjungan->DOKTER_ID;

        if($statusjenis==="NORMAL"){
            $resultkeluhanutama   = $this->md->keluhanutama($episodeid);
        }else{
            if($statusjenis==="BAYI"){
                $resultkeluhanutama          = $this->md->keluhanutamabayibarulahir($episodeid);
                $resultkeluhanutamaspesialis = $this->md->keluhanutamabayibarulahirdokter($episodeid);
            }else{
                if($statusjenis==="NPP"){
                    $resultkeluhanutama          = $this->md->keluhanutamabayibarulahirnicu($episodeid);
                    $resultkeluhanutamaspesialis = $this->md->keluhanutamabayibarulahirnicudokter($episodeid);

                    if(empty($resultkeluhanutamaspesialis)){
                        $resultkeluhanutamaspesialis = $this->md->keluhanutamabayibarulahirnicuperawat($episodeid);
                    }
                }else{
                    if($statusjenis==="PERINA"){
                        $resultkeluhanutama          = $this->md->keluhanutamabayibarulahirperina($episodeid);
                        $resultkeluhanutamaspesialis = $this->md->keluhanutamabayibarulahirperinadokter($episodeid);
                    }else{
                        $resultkeluhanutama          = $this->md->keluhanutamaranappoli($episodeid);
                        $resultkeluhanutamaspesialis = $this->md->keluhanutamapoli($resultkunjungan->POLIIDLAST,$resultkunjungan->PASIEN_ID);
                    }
                }
            }
        }

        if(empty($resultkeluhanutama)){
            $body['status']                = false;
            $body['code']                  = 404;
            $body['message']               = "Source Data Tidak Tersedia";
            $body['metadata']['timestamp'] = date('Y-m-d H:i:s');

            return $this->response($body, 404);
        }
        
        $resultobat           = $this->md->obat($episodeid);
        $resultradiologi      = $this->md->radiologi($episodeid);
        $resultlaboratoriumhd = $this->md->laboratoriumhd($episodeid);
        $resultdiagnosa       = $this->md->diagnosa($episodeid);
        $resultusg            = $this->md->hasilusg($episodeid);

        if($statusjenis==="POLI"){
            $sourcedata['riwayat']['keluhanutama']           = $this->keluhanutama($resultkeluhanutamaspesialis);
            $sourcedata['riwayat']['gejala']                 = $this->gejala($statusjenis,$resultkeluhanutamaspesialis);
        }else{
            $sourcedata['riwayat']['keluhanutama']           = $this->keluhanutama($resultkeluhanutama);
            $sourcedata['riwayat']['gejala']                 = $this->gejala($statusjenis,$resultkeluhanutama);
        }
        
        
        $sourcedata['riwayat']['sekarang'] = $this->sekarang($statusjenis,$resultkeluhanutama);
        $sourcedata['riwayat']['dahulu']   = $this->dahulu($resultkeluhanutama);

        if($statusjenis==="NORMAL"){
            $sourcedata['pemeriksaanfisik']['ttv']           = $this->ttv($resultkeluhanutama);
            $sourcedata['pemeriksaanfisik']['statuslokalis'] = $this->statuslokalis($statusjenis,$resultkeluhanutama);
            $sourcedata['diagnosis']['indikasiranap']        = $this->indikasiranap($statusjenis,$resultkeluhanutama);
        }else{
            if($statusjenis==="POLI"){
                $sourcedata['pemeriksaanfisik']['ttv']           = $this->ttv($resultkeluhanutama);
                $sourcedata['pemeriksaanfisik']['statuslokalis'] = $this->statuslokalis($statusjenis,$resultkeluhanutamaspesialis);
                $sourcedata['diagnosis']['indikasiranap']        = $this->indikasiranap($statusjenis,$resultkeluhanutamaspesialis);
            }else{
                $sourcedata['pemeriksaanfisik']['ttv']           = $this->ttv($resultkeluhanutamaspesialis);
                $sourcedata['pemeriksaanfisik']['statuslokalis'] = $this->statuslokalis($statusjenis,$resultkeluhanutamaspesialis);
                $sourcedata['diagnosis']['indikasiranap']        = $this->indikasiranap($statusjenis,$resultkeluhanutamaspesialis);
            }
        }
        
        $sourcedata['diagnosis']['icd10']                = $this->icd10($resultdiagnosa);
        $sourcedata['kontrolulang']                      = $this->kontrolulang($statusjenis,$resultkunjungan,$dokterid,$episodeid);
        $sourcedata['segeradibawa']                      = $this->segaradibawa($resultkunjungan);
        $sourcedata['penunjang']['obat']['perawatan']    = $this->obatperawat($resultobat);
        $sourcedata['penunjang']['obat']['pulang']       = $this->obatpulang($resultobat);
        $sourcedata['penunjang']['radiologi']            = $this->radiologi($resultradiologi,$resultusg);
        $sourcedata['penunjang']['laboratorium']         = $this->laboratorium($resultkunjungan,$resultlaboratoriumhd);
        

        $body['status']                = true;
        $body['code']                  = 200;
        $body['message']               = "success";
        $body['sourcedata'][]          = $sourcedata;
        $body['transaksi']             = $this->kunjungan($resultkunjungan);
        $body['metadata']['timestamp'] = date('Y-m-d H:i:s');

        $dataresume['PASIEN_ID']         = $body['transaksi']['pasienid'];
        $dataresume['EPISODE_ID']        = $body['transaksi']['episodeid'];
        $dataresume['DOKTER_ID']         = $body['transaksi']['dokterid'];
        $dataresume['RUANG_ID']          = $body['transaksi']['ruangid'];
        $dataresume['CREATED_BY']        = $body['transaksi']['dokterid'];
        $dataresume['KONDISI']           = $body['transaksi']['pulang'];
        $dataresume['KONDISI_PULANG_ID'] = $body['transaksi']['pulangid'];
        $dataresume['KELUHAN']           = $body['sourcedata'][0]['riwayat']['keluhanutama']['text'];
        $dataresume['GEJALA']            = $body['sourcedata'][0]['riwayat']['gejala']['text'];
        $dataresume['RIWAYATPS']         = $body['sourcedata'][0]['riwayat']['sekarang']['text'];
        $dataresume['RIWAYATPD']         = $body['sourcedata'][0]['riwayat']['dahulu']['text'];
        $dataresume['STATUS']            = $body['sourcedata'][0]['pemeriksaanfisik']['statuslokalis']['text'];
        $dataresume['VITAL']             = $body['sourcedata'][0]['pemeriksaanfisik']['ttv']['text'];
        $dataresume['INDIKASI']          = $body['sourcedata'][0]['diagnosis']['indikasiranap']['text'];
        $dataresume['LAINNYA']           = $body['sourcedata'][0]['penunjang']['radiologi']['text'];
        $dataresume['OBATP']             = $body['sourcedata'][0]['penunjang']['obat']['pulang']['text'];
        $dataresume['KONTROL']           = $body['sourcedata'][0]['kontrolulang']['text'];
        $dataresume['INTRUKSI']          = $body['sourcedata'][0]['segeradibawa']['text'];
        $dataresume['SHOW_ITEM']         = "1";

        $resultcekdata = $this->md->cekdata($body['transaksi']['episodeid']);

        if(empty($resultcekdata)){
            $this->md->insertresume($dataresume);
        }else{
            $this->md->updateresume($body['transaksi']['episodeid'],$dataresume);
        }
        
        $this->response($body, 200);
    }

    public function kunjungan($result){
        $body = [];

        $body['pasienid']      = $result->PASIEN_ID;
        $body['episodeid']     = $result->EPISODE_ID;
        $body['statusepisode'] = $result->STATUS_EPISODE;
        $body['ruangid']       = $result->RUANGRWT_ID;
        $body['mrpasien']      = $result->MRPASIEN;
        $body['namapasien']    = $result->NAMAPSIEN;
        $body['pulangid']      = $result->PULANG_ID;
        $body['pulang']        = $result->CARAPULANG;
        $body['tglkeluar']     = $result->TGLKELUAR;
        $body['dokterid']      = $result->DOKTER_ID;
        $body['namadokter']    = $result->NAMADOKTER;

        return $body;
    }

    public function icd10($result){
        $body = [];
        foreach ($result as $a) {
            $item             = [];
            $item['icd10_id']   = $a['ICD10'];
            $item['icd10_desc'] = $a['DIAGNOSA'];

            $body['raw'][] = $item;
        }

        return $body;
    }

    public function obatperawat($result){
        $body = [
            'raw' => []
        ];

        foreach ($result as $a) {
            $item             = [];
            $item['obatid']   = $a['OBAT_ID'];
            $item['namaobat'] = $a['NAMA_OBAT'];

            if ($a['JENIS_RESEP'] == "1") {
                $body['raw'][] = $item;
            }
        }

        $body['text'] = implode("\n", array_map(function ($item) {
            return "- {$item['namaobat']}";
        }, $body['raw']));

        $body['len'] = mb_strlen($body['text']);

        return $body;
    }

    public function obatpulang($result){
        $body = [];

        if(!empty($result)){
            $body['raw'] = [];

            foreach ($result as $a) {
                $item             = [];
                $item['obatid']   = $a['OBAT_ID'];
                $item['namaobat'] = $a['NAMA_OBAT'];

                if ($a['JENIS_RESEP'] != "1") {
                    $body['raw'][] = $item;
                }
            }

            $body['text'] = !empty($body['raw']) ? implode("\n", array_map(function($item) { return "- {$item['namaobat']}"; }, $body['raw'])) : "";
            $body['len']  = mb_strlen($body['text']);
        }else{
            $body['raw']  = [];
            $body['text'] = "";
            $body['len']  = 0;
        }
        
        return $body;
    }

    public function radiologi($result, $resultusg){
        $body = [
            'raw'    => [],
            'text'   => '',
            'len'    => 0,
            'baseon' => [
                'usg' => ''
            ]
        ];

        // =========================
        // DEFAULT
        // =========================
        $hasilUSG = '';

        // simpan raw usg
        if (!empty($resultusg)) {
            $body['baseon']['usg'] = $resultusg->O;
        }

        // =========================
        // HASIL USG
        // =========================
        if (!empty($resultusg)) {

            $hasilUSG = $this->extractHasilUSG($resultusg->O);

            if (!empty($hasilUSG)) {

                $body['raw'][] = [
                    'namapemeriksaan' => 'USG',
                    'result'          => $hasilUSG,
                    'createddate'     => $resultusg->CREATEDDATE
                ];
            }
        }

        // =========================
        // HASIL RADIOLOGI
        // =========================
        if (!empty($result)) {

            foreach ($result as $a) {

                // skip kalau result kosong
                if (empty(trim($a['RESULT'] ?? ''))) {
                    continue;
                }

                $item = [];

                $item['namapemeriksaan'] = $a['NAMAPEMERIKSAAN'] ?? '';
                $item['result']          = $a['RESULT'] ?? '';
                $item['createddate']     = $a['CREATEDDATE'] ?? '';

                $body['raw'][] = $item;
            }
        }

        // =========================
        // TEXT FINAL
        // =========================
        if (!empty($body['raw'])) {

            $body['text'] = implode(
                "\n\n",
                array_map(function ($item) {

                    return trim(
                        $item['createddate'] . " " .
                        $item['namapemeriksaan']
                    ) .
                    "\nConclusion:\n" .
                    trim($item['result']);

                }, $body['raw'])
            );

            $body['len'] = mb_strlen($body['text']);
        }

        return $body;
    }

    public function extractHasilUSG($text){
        if (!$text || !is_string($text)) {
            return '';
        }

        $hasilUSG = '';

        if (preg_match('/(Hasil\s+USG.*?)(\n\s*\n|$)/is', $text, $match)) {
            $hasilUSG = trim($match[1]);
        }

        return $hasilUSG;
    }
    
    public function laboratorium($kunjungan, $result){

        $body        = [];
        $body['raw'] = [];

        $norm = $kunjungan->MRPASIEN;

        foreach ($result as $a) {

            $item = [];
            $item['sampelid']    = $a['SAMPEL_ID'];
            $item['registerid']  = $a['REGISTRASI_ID'];
            $item['createddate'] = $a['CREATEDDATE'];
            $item['hasil']       = [];

            $resultlaboratoriumdt = $this->md->laboratoriumdt($a['SAMPEL_ID'], $norm);

            foreach ($resultlaboratoriumdt as $rowdetail) {
                $item['hasil'][] = [
                    'namates' => $rowdetail['NAMA_TES'] ?? '',
                    'unit'    => $rowdetail['UNITS'] ?? '',
                    'result'  => $rowdetail['RESULT_VALUE'] ?? '',
                    'flag'    => $rowdetail['RESULT_FLAG'] ?? ''
                ];
            }

            $body['raw'][] = $item;
        }

        $body['raw'] = $body['raw'];

        $body['text'] = implode("\n\n", array_map(function($item){
            $header = $item['createddate'] . " Sampel: " . $item['sampelid'];
            $detail = "";

            foreach ($item['hasil'] as $h) {
                $detail .= "- {$h['namates']} : {$h['result']} {$h['unit']} {$h['flag']}\n";
            }

            return $header . "\n" . trim($detail);
        }, $body['raw']));

        $body['len'] = mb_strlen($body['text']);

        return $body;
    }

    public function gejala($statusjenis,$result){

        if($statusjenis==="NORMAL"){
            $text     = trim($result->S2);
            $parsed   = $this->extractPositiveClinicalFlags($text);
            $joined   = implode(", ", $parsed['raw']);
            $symptoms = $this->extractSymptomsPerItem($joined);
        }else{
            if($statusjenis==="POLI"){
                $text             = trim($result->S);
                $symptoms['raw']  = [];
                $symptoms['text'] = trim($result->S);
            }else{
                $text             = "-";
                $symptoms['raw']  = [];
                $symptoms['text'] = "-";
            }
        }

        return [
            "baseon" => $text,
            "raw"    => $symptoms['raw'],
            "text"   => $symptoms['text'],
            "len"    => mb_strlen($symptoms['text'])
        ];
    }

    public function sekarang($statusjenis,$result){

        // =========================
        // AMBIL S2 & P
        // =========================
        $s2 = trim($result->S2 ?? "");
        $p  = trim($result->P ?? "");

        if($statusjenis==="NORMAL"){
            

            // =========================
            // NORMALISASI
            // =========================
            $clean = function($text){

                $text = str_replace("\r", "\n", $text);
                $text = preg_replace('/\n{2,}/', "\n", $text);
                $text = preg_replace('/[ \t]+/', ' ', $text);

                return trim($text);
            };

            $s2 = $clean($s2);
            $p  = $clean($p);

            // =========================
            // AMBIL HANYA BAGIAN TERAPI DI P
            // =========================
            if ($p !== "" && preg_match('/rencana\s*terapi/i', $p)) {

                $parts = preg_split(
                    '/rencana\s*terapi\s*(di)?\s*perawatan/i',
                    $p
                );

                $p = trim($parts[1] ?? "");
            }

            // =========================
            // FILTER BARIS P
            // =========================
            $lines = preg_split('/\n+/', $p);

            $cleanedP = [];

            foreach ($lines as $line){

                $line  = trim($line);
                $lower = strtolower($line);

                if ($line === "") {
                    continue;
                }

                // hapus "USUL :"
                if (preg_match('/^usul\s*:/i', $lower)) {
                    continue;
                }

                // skip riwayat
                if (
                    strpos($lower, 'riwayat sosial') !== false ||
                    strpos($lower, 'riwayat nikah') !== false ||
                    strpos($lower, 'riwayat obstetri') !== false ||
                    strpos($lower, 'rpd') !== false ||
                    strpos($lower, 'rpk') !== false ||
                    strpos($lower, 'rpo') !== false
                ) {
                    continue;
                }

                $cleanedP[] = $line;
            }

            $p = implode("\n", $cleanedP);

            // =========================
            // PILIH SALAH SATU
            // PRIORITAS S2
            // =========================
            if ($s2 !== "") {
                $output = $s2;
            } else {
                $output = $p;
            }

            return [
                "text" => $output,
                "len"  => mb_strlen($output)
            ];
        }else{
            return [
                "text" => $s2,
                "len"  => mb_strlen($s2)
            ];
        }
    }

    public function dahulu($result){
        $text = trim($result->S3) ?? "";

        if($text === ""){
            $text = trim($result->O) ?? "";
            $parsed = preg_match('/(?:Riwayat|Riw)\s*:?\s*\R*([^\r\n]+)/i',$text,$matches) ? [ 'text' => trim($matches[1]), 'raw'  => [trim($matches[1])]]: null;
        }else{
            $parsed = $this->extractRiwayatClinical($text);
        }

        if(empty($parsed) || !is_array($parsed) || empty($parsed['text'])){
            return [
                "baseon" => $text,
                "raw"    => preg_split('/\n+/', trim($text)),
                "text"   => $text,
                "len"    => mb_strlen($text)
            ];
        }

        return [
            "baseon" => $text,
            "raw"    => is_array($parsed['raw'] ?? null)
                ? $parsed['raw']
                : preg_split('/\n+/', trim($parsed['raw'] ?? $text)),

            "text"   => $parsed['text'],
            "len"    => mb_strlen($parsed['text'])
        ];
    }

    public function keluhanutama($result){
        $body = [];

        $text = isset($result->S) ? trim($result->S) : '';

        $body['text'] = $text;
        $body['len']  = mb_strlen($text);

        return $body;
    }

    public function statuslokalis($statusjenis,$result){
        
        if($statusjenis==="NORMAL"){
            $text = trim($result->O);
            $parsed = $this->extractStatusLokalis($text);
        }else{
            if($statusjenis==="POLI"){
                $text           = trim($result->O);
                $parsed['raw']  = [];
                $parsed['text'] = $text;
            }else{
                $text = trim($result->S);
                $parsed = $this->extractAPGAR($text);

                if(empty($parsed['text'])){
                    $text = trim($result->O);
                    $parsed = $this->extractAPGAR($text);
                }

                if(empty($parsed['text'])){

                    $text = trim($result->STATUSLOKALISOK ?? '');

                    // ambil baris status lokalis
                    preg_match_all(
                        '/(UUB.*|retraksi.*|abd.*|akral.*)/iu',
                        $text,
                        $matches
                    );

                    $statusLokalis = trim(implode("\n", $matches[0]));

                    $parsed['text'] = $statusLokalis;
                    $parsed['raw']  = $matches[0];
                }

                if (empty($parsed['text'])) {
                    $text = trim($result->O);
                    $parsed = $this->extractStatusLokalis($text);
                }
            }
        }

        return [
            "baseon" => $text,
            "raw"    => $parsed['raw'],
            "text"   => $parsed['text'],
            "len"    => mb_strlen($parsed['text'])
        ];
        
    }

    public function ttv($result){
        $text = trim($result->O);

        $parsed = $this->extractTtv($text);

        return [
            "baseon" => $text,
            "raw"    => $parsed['raw'],
            "text"   => $parsed['text'],
            "len"    => mb_strlen($parsed['text'])
        ];
    }

    public function indikasiranap($statusjenis,$result){
        $body = [];

        $text = trim($result->A);

        $body['text'] = $text;
        $body['len'] = mb_strlen($text);

        return $body;
    }

    public function kontrolulang($statusjenis,$result,$dokterid,$episodeid){
        $body = [];

        if($result->PULANG_ID === "P01"){
            if($statusjenis==="NORMAL" || $statusjenis==="NPP" || $statusjenis==="PERINA" || $statusjenis==="POLI"){
                if($dokterid === "DR. A0000000010"){
                    $resultoperasi = $this->md->cekttindakanoperasi($episodeid);
                    if(!empty($resultoperasi)){
                        $text = "Kontrol ulang 1 Minggu ke ".$result->POLIKLINIK.", Kontrol Luka Post Operasi";
                    }else{
                        $text = "Kontrol ulang 1 Minggu ke ".$result->POLIKLINIK;
                    }
                }else{
                    $text = "Kontrol ulang 1 Minggu ke ".$result->POLIKLINIK;
                }
            }else{
                $text = "Kontrol ulang 1 Minggu ke fasilitas kesehatan pertama";
            }
        }else{
            $text = "-";
        }

        $body['text'] = $text;
        $body['len']  = mb_strlen($text);

        return $body;
    }

    public function segaradibawa($result){
        $body = [];

        if($result->PULANG_ID === "P01"){
            $text = "Dibawa kembali ke fasilitas kesehatan apabila terjadi perburukan kondisi";
        }else{
            $text = "-";
        }

        $body['text'] = $text;
        $body['len']  = mb_strlen($text);

        return $body;
    }

    public function extractPositiveClinicalFlags($text){

        $raw = [];

        $positiveMarkers = [
            'keluhan',
            'mengeluh',
            'dirasakan',
            'terdapat',
            'ada',
            'mengalami',
            'tampak',
            'disertai',
            'positif'
        ];

        $negativeMarkers = [
            'tidak',
            'tidak ada',
            'tidak terdapat',
            'tidak disertai',
            'tidak mengalami',
            'disangkal',
            'menyangkal',
            'tanpa',
            'belum ada',
            'negatif'
        ];

        // =========================
        // SPLIT KALIMAT (AMAN DARI 23.00 / 04.30)
        // =========================
        $text      = preg_replace('/(?<=\d)\.(?=\d)/', '<DOT>', trim($text));
        $sentences = preg_split('/(?<=[\.\!\?])\s+|\n+/', $text);

        foreach ($sentences as $sentence){

            $sentence = trim($sentence);
            if ($sentence === '') continue;

            $sentence = str_replace('<DOT>', '.', $sentence);

            $lower = strtolower($sentence);

            $positive = false;
            $negative = false;

            // SYMBOL
            if (preg_match('/\(\+\)|\+/', $lower)) $positive = true;
            if (preg_match('/\(-\)/', $lower)) $negative = true;

            // KEYWORDS
            foreach ($negativeMarkers as $neg){
                if (strpos($lower, $neg) !== false) $negative = true;
            }

            foreach ($positiveMarkers as $pos){
                if (strpos($lower, $pos) !== false) $positive = true;
            }

            if ($negative && !$positive) continue;

            if ($positive){

                $clean = $this->removeNegativeSegments($sentence);

                if ($clean !== ''){
                    $raw[] = $clean;
                }
            }
        }

        return [
            "raw"  => array_values(array_unique($raw)),
            "text" => implode("\n", $raw)
        ];
    }

    public function removeNegativeSegments($sentence){

        // hapus "xxx (-)"
        $sentence = preg_replace('/[^,\.]+?\(-\)/i', '', $sentence);

        // hapus "xxx-"
        $sentence = preg_replace('/\b\w+-(?=\s|,|\.|$)/i', '', $sentence);

        // rapikan koma
        $sentence = preg_replace('/\s*,\s*/', ', ', $sentence);
        $sentence = preg_replace('/(,\s*)+/', ', ', $sentence);

        // hapus koma di awal/akhir
        $sentence = trim($sentence, " ,.");

        // rapikan spasi
        $sentence = preg_replace('/\s{2,}/', ' ', $sentence);

        return $sentence;
    }

    public function extractSymptomsPerItem($text){

        $items = [];

        $text = strtolower($text);

        // =========================
        // NORMALISASI
        // =========================
        $text = preg_replace('/\r/', '', $text);
        $text = preg_replace('/\n+/', "\n", $text);

        // 🔥 FIX: amankan angka desimal (23.00 / 04.30)
        $text = preg_replace('/(?<=\d)\.(?=\d)/', '<DOT>', $text);

        // =========================
        // SPLIT KALIMAT
        // =========================
        $sentences = preg_split('/\n+|\./', $text);

        foreach ($sentences as $sentence){

            $sentence = trim($sentence);

            if ($sentence === '') continue;

            // restore titik desimal
            $sentence = str_replace('<DOT>', '.', $sentence);

            // =========================
            // HAPUS PREFIX UMUM
            // =========================
            $sentence = preg_replace(
                '/^(pasien|datang|dengan|keluhan)\s*/i',
                '',
                $sentence
            );

            // =========================
            // SPLIT PER ITEM
            // =========================
            $parts = preg_split('/,/', $sentence);

            foreach ($parts as $part){

                $part = trim($part);

                if ($part === '') continue;

                // =========================
                // BERSIHKAN PREFIX KECIL
                // =========================
                $part = preg_replace(
                    '/^(dan|serta|disertai)\s*/i',
                    '',
                    $part
                );

                $part = trim($part);

                if ($part === '') continue;

                // =========================
                // SKIP NEGATIF
                // =========================
                if (
                    preg_match('/\(-\)/i', $part)
                    ||
                    preg_match('/-\s*$/', $part)
                    ||
                    preg_match('/\b(tidak|disangkal|menyangkal|tanpa|negatif)\b/i', $part)
                ) {
                    continue;
                }

                // =========================
                // HARUS ADA GEJALA / POSITIF
                // =========================
                if (
                    !preg_match(
                        '/
                        \(\+\)
                        |
                        \+
                        |
                        \b(
                            sesak|
                            nyeri|
                            mual|
                            muntah|
                            demam|
                            lemas|
                            batuk|
                            bengkak|
                            pucat|
                            pusing|
                            diare|
                            kejang|
                            pilek|
                            kembung
                        )\b
                        /ix',
                        $part
                    )
                ) {
                    continue;
                }

                // =========================
                // CLEAN SYMBOL (+) TANPA MERUSAK +- (DURASI)
                // =========================

                // hapus hanya (+)
                $part = str_replace('(+)', '', $part);

                // hapus + tunggal tapi JANGAN ganggu +- (durasi)
                $part = preg_replace('/(?<!-)\+(?!-)/', '', $part);

                $part = trim($part);

                if (mb_strlen($part) < 3) continue;

                $items[] = ucfirst($part) . ' (+)';
            }
        }

        // =========================
        // UNIQUE
        // =========================
        $items = array_values(array_unique($items));

        return [
            "raw"  => $items,
            "text" => implode("\n", $items)
        ];
    }

    public function extractTtv($text){

        $lines = preg_split('/\n+/', $text);

        $raw = [];

        // =========================
        // MASTER KEYWORDS
        // =========================
        $ttvKeywords = [
            'ku',
            'kes',
            'Keadaan',
            'kesadaran',
            'gcs',

            // tekanan darah
            'tekanan darah',
            'td',

            // nadi
            'frekuensi nadi',
            'nadi',
            'hr',

            // napas
            'frekuensi napas',
            'napas',
            'respirasi',
            'rr',

            // suhu
            'suhu',
            't°',
            'temp',
            'temperature',

            // saturasi
            'saturasi',
            'spo2',

            // antropometri
            'bb',
            'tb',

            'ews'
        ];

        $stopKeywords = [
            'Thorax',
            'kepala',
            'mata',
            'hidung',
            'mulut',
            'leher',
            'jantung',
            'paru',
            'abdomen',
            'integumen',
            'extremitas',
            'hematologi',
            'kimia darah',
            'elektrolit',
            'imuno',
            'fungsi hati',
            'fungsi ginjal'
        ];

        // =========================
        // HELPER
        // =========================
        $matchKeyword = function ($line, $keywords) {

            foreach ($keywords as $keyword) {

                if (stripos($line, $keyword) !== false) {
                    return true;
                }
            }

            return false;
        };

        // =========================
        // PARSING
        // =========================
        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $lower = strtolower($line);

            // =========================
            // STOP SECTION
            // =========================
            if (
                preg_match(
                    '/^(' . implode('|', $stopKeywords) . ')\b/i',
                    $line
                ) ||
                preg_match('/^\./', $line)
            ) {
                break;
            }

            // =========================
            // AMBIL TTV
            // =========================
            if (

                // keyword utama
                $matchKeyword($lower, $ttvKeywords)

                ||

                // satuan umum TTV
                preg_match(
                    '/(
                        mmhg|
                        bpm|
                        celcius|
                        °c|
                        %|
                        kg|
                        cm|
                        kali\/mnt|
                        x\/menit|
                        x\/mnt|
                        \/menit
                    )/ix',
                    $line
                )
            ) {

                $raw[] = $line;
            }
        }

        // =========================
        // HAPUS DUPLIKAT
        // =========================
        $raw = array_values(array_unique($raw));

        $finalText = implode("\n", $raw);

        return [
            "raw"  => $raw,
            "text" => $finalText,
            "len"  => mb_strlen($finalText)
        ];
    }

    public function extractStatusLokalis($text){
        $lines = preg_split('/\n+/', $text);

        $raw   = [];
        $clean = [];

        $allowed = false;
        $currentOrgan = null;

        // =========================
        // CONFIG
        // =========================
        $startKeywords = [
            'status lokalis',
            'status generalis'
        ];

        $organKeywords = [
            'status generalis',
            'kepala','mata','hidung','mulut','leher',
            'jantung','paru','abdomen','integumen',
            'ekstremitas','extremitas','thorax','telinga','tht'
        ];

        $stopKeywords = [
            'hematologi','kimia darah','elektrolit','imuno',
            'serologi','fungsi hati','fungsi ginjal',
            'diabetes','lab','gds','pemeriksaan',
            'diagnosa','dx','tx','terapi'
        ];

        // helper
        $matchKeyword = function ($line, $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($line, $keyword) !== false) return true;
            }
            return false;
        };

        foreach ($lines as $line) {

            $line = trim($line);
            if ($line === '') continue;

            $lower = strtolower($line);

            // =========================
            // STOP
            // =========================
            if ($matchKeyword($lower, $stopKeywords)) {
                $allowed = false;
                $currentOrgan = null;
                continue;
            }

            // =========================
            // START (HEADER)
            // =========================
            if ($matchKeyword($lower, $startKeywords)) {
                $allowed = true;

                $currentOrgan = ucfirst($lower);

                $raw[] = $line;
                $clean[] = $line;

                continue;
            }

            // =========================
            // 🔥 AUTO START JIKA KETEMU ORGAN
            // =========================
            if (preg_match('/^(' . implode('|', array_map('preg_quote', $organKeywords)) . ')\s*[:=]/i', $line)) {
                $allowed = true;
            }

            if (!$allowed) continue;

            // =========================
            // ORGAN BARU
            // =========================
            if (preg_match('/^(' . implode('|', array_map('preg_quote', $organKeywords)) . ')\s*[:=]/i', $line, $match)) {
                $currentOrgan = ucfirst(strtolower($match[1]));

                $raw[] = $line;
                $clean[] = $line;
                continue;
            }

            // =========================
            // SUB ORGAN (Mata:, THT:, dll)
            // =========================
            if (preg_match('/^[A-Za-z ]+\s*[:=]/', $line) && $currentOrgan !== null) {
                $raw[] = $line;
                $clean[] = $line;
                continue;
            }

            // =========================
            // LANJUTAN
            // =========================
            if ($currentOrgan !== null) {
                $raw[] = $line;
                $clean[] = $line;
            }
        }

        $finalText = implode("\n", $clean);

        return [
            "raw"  => $raw,
            "text" => $finalText,
            "len"  => mb_strlen($finalText)
        ];
    }

    public function extractAPGAR($text){
        $lines   = preg_split('/\R/', $text);
        $raw     = [];
        $clean   = [];
        $allowed = false;

        foreach ($lines as $line) {

            $line = trim($line);

            // START FROM APGAR
            if (!$allowed && stripos($line, 'apgar') !== false) {
                $allowed = true;
            }

            // BELUM MASUK BLOK APGAR
            if (!$allowed) {
                continue;
            }

            // STOP SAAT ENTER KOSONG
            if ($line === '') {
                break;
            }

            $raw[]   = $line;
            $clean[] = $line;
        }

        $finalText = implode("\n", $clean);

        return [
            "raw"  => $raw,
            "text" => $finalText,
            "len"  => mb_strlen($finalText)
        ];
    }

    public function extractRiwayatClinical($text, $config = []){
        // =========================
        // DEFAULT CONFIG
        // =========================
        $defaults = [

            "startKeywords" => [
                'rw', 'riw', 'riwayat', 'h/o', 'history',
                'ckd', 'dm', 'hipertensi',
                'dx', 'tx'
            ],

            "stopKeywords" => [
                'poli', 'waktu', 'usg'
            ],

            "negativeKeywords" => [
                'disangkal',
                'tidak diketahui'
            ],

            "negativeSymbols" => [
                '(-)', '-/-'
            ],

            "headers" => [
                'dx' => 'Dx:',
                'tx' => 'Tx:'  
            ]
        ];

        $cfg = array_merge($defaults, $config);

        $lines = preg_split('/\n+/', $text);

        $raw = [];
        $formatted = [];

        $inHistoryBlock = false;

        $skipSocialBlock = false;
        $skipPernikahan  = false;

        // =========================
        // 🔥 OBSTETRI STATE
        // =========================
        $obstetriMode = false;
        $currentObstetri = null;
        $obstetriList = [];

        $seenHeaders = array_fill_keys(array_keys($cfg['headers']), false);

        foreach ($lines as $line) {

            $line = trim($line);
            if ($line === '') continue;

            $lower = strtolower($line);

            // =========================
            // 🔥 SKIP RIWAYAT PERNIKAHAN FULL BLOCK
            // =========================
            if (strpos($lower, 'riwayat pernikahan') !== false) {
                $skipPernikahan = true;
                continue;
            }

            if ($skipPernikahan) {
                if (preg_match('/^riwayat\s+/i', $lower) && strpos($lower, 'riwayat pernikahan') === false) {
                    $skipPernikahan = false;
                } else {
                    continue;
                }
            }

            // =========================
            // 🔥 SKIP RPK TOTAL
            // =========================
            if (strpos($lower, 'rpk') !== false) {
                continue;
            }

            // =========================
            // 🔥 RPD CLEAN (FIRST SENTENCE ONLY)
            // =========================
            if (strpos($lower, 'rpd') !== false) {
                $parts = preg_split('/\.\s*/', $line);
                $line = $parts[0];
            }

            // =========================
            // 🔥 SKIP RIWAYAT SOSIAL BLOCK
            // =========================
            if (strpos($lower, 'riwayat sosial') !== false) {
                $skipSocialBlock = true;
                continue;
            }

            if ($skipSocialBlock) {
                continue;
            }

            // =========================
            // START DETECTION
            // =========================
            foreach ($cfg['startKeywords'] as $kw) {
                if (strpos($lower, $kw) !== false) {
                    $inHistoryBlock = true;
                    break;
                }
            }

            if (strpos($line, '+') !== false || strpos($line, '?') !== false) {
                $inHistoryBlock = true;
            }

            // =========================
            // STOP DETECTION
            // =========================
            foreach ($cfg['stopKeywords'] as $kw) {
                if (strpos($lower, $kw) !== false) {
                    $inHistoryBlock = false;
                    break;
                }
            }

            if (!$inHistoryBlock) continue;

            // =========================
            // SPLIT PER ITEM
            // =========================
            $line = preg_replace('/\s*,\s*/', ' | ', $line);
            $chunks = explode(' | ', $line);

            foreach ($chunks as $chunk) {

                $chunk = trim($chunk);
                $chunk = preg_replace('/\x{00A0}/u', '', $chunk);
                $chunk = preg_replace('/\s+/', ' ', $chunk);

                if ($chunk === '') continue;

                $lowerChunk = strtolower($chunk);

                // =========================
                // 🔥 EXCLUDE SOCIAL DETAIL
                // =========================
                if (preg_match('/\b(pasien|suami|karyawan|pendidikan|sma|stm|bekerja)\b/i', $lowerChunk)) {
                    continue;
                }

                // =========================
                // 🔥 EXCLUDE LIFESTYLE
                // =========================
                if (preg_match('/\b(merokok|minum alkohol|alkohol)\b/i', $lowerChunk)) {
                    continue;
                }

                // =========================
                // 🔥 OBSTETRI DETECTOR
                // =========================
                if (preg_match('/riwayat obstetri/i', $chunk)) {

                    $obstetriMode = true;
                    $currentObstetri = $chunk;

                    $raw[] = $chunk;
                    $formatted[] = $chunk;

                    continue;
                }

                // =========================
                // 🔥 OBSTETRI GROUPING FIX
                // =========================
                if ($obstetriMode) {

                    if (preg_match('/^\d+\./', $chunk)) {

                        if ($currentObstetri !== null) {
                            $obstetriList[] = $currentObstetri;
                        }

                        $currentObstetri = $chunk;
                        continue;
                    }

                    if ($currentObstetri !== null) {
                        $currentObstetri .= ', ' . $chunk;
                    }

                    continue;
                }

                // =========================
                // NEGATIVE FILTER
                // =========================
                $isNegative = false;

                foreach ($cfg['negativeKeywords'] as $neg) {
                    if (strpos($lowerChunk, $neg) !== false) {
                        $isNegative = true;
                        break;
                    }
                }

                foreach ($cfg['negativeSymbols'] as $sym) {
                    if (strpos($chunk, $sym) !== false) {
                        $isNegative = true;
                        break;
                    }
                }

                if (preg_match('/-\s*$/', $chunk)) {
                    $isNegative = true;
                }

                if ($isNegative) continue;

                // =========================
                // HEADER HANDLING
                // =========================
                foreach ($cfg['headers'] as $key => $label) {

                    if (preg_match('/^' . preg_quote($key, '/') . '\s*:/i', $chunk)) {

                        if ($seenHeaders[$key]) continue;

                        $seenHeaders[$key] = true;

                        $raw[] = $label;
                        $formatted[] = $label;

                        continue 2;
                    }
                }

                // =========================
                // FINAL CLEAN
                // =========================
                $chunk = trim($chunk);
                

                if ($chunk === '') continue;

                $raw[] = $chunk;
                $formatted[] = $chunk;
            }
        }

        // =========================
        // FLUSH OBSTETRI LAST ITEM
        // =========================
        if ($currentObstetri !== null) {
            $obstetriList[] = $currentObstetri;
        }

        // =========================
        // MERGE OBSTETRI RESULT
        // =========================
        if (!empty($obstetriList)) {
            $raw = array_merge($raw, $obstetriList);
            $formatted = array_merge($formatted, $obstetriList);
        }

        // =========================
        // FINAL OUTPUT
        // =========================
        $raw = array_values(array_unique($raw));
        $formatted = array_values(array_unique($formatted));

        return [
            "raw"   => $raw,
            "text"  => implode("\n", $formatted),
            "count" => count($raw)
        ];
    }

    public function generateresume_POST(){
        headerlogresume();

        $resultlistrresume = $this->md->listrresume();

        if(empty($resultlistrresume)){
            echo color('red')."Data Tidak Ditemukan";
            return;
        }

        foreach($resultlistrresume as $a){
            $statusColor = "red";
            $statusMsg   = "";

            $episodeid = $a->EPISODE_ID;

            // =========================
            // CALL API
            // =========================
            $url = "http://192.168.200.41:8080/analisadata/index.php/generateresumeai/".$episodeid;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['episodeid' => $episodeid]);
            $response = curl_exec($ch);

            // =========================
            // ERROR CURL
            // =========================
            if (curl_errno($ch)) {

                $statusMsg   = "CURL ERROR: ".curl_error($ch);
                $statusColor = "red";

                curl_close($ch);

                echo formatlog($a->PASIEN_ID,$a->EPISODE_ID,$a->TGLKELUAR,$a->DOKTER_ID,$statusMsg,'cyan','cyan','cyan','cyan',$statusColor);
                continue;
            }

            curl_close($ch);

            // =========================
            // DECODE JSON
            // =========================
            $result = json_decode($response, true);

            if (!$result) {
                $statusMsg   = "INVALID JSON";
                $statusColor = "red";
            } else {
                if (isset($result['status']) && $result['status'] === true && $result['code'] == 200) {
                    $statusMsg   = "Success";
                    $statusColor = "green";

                    echo formatlog($a->PASIEN_ID,$a->EPISODE_ID,$a->TGLKELUAR,$a->DOKTER_ID,$statusMsg,'cyan','cyan','cyan','cyan',$statusColor);
                } else {

                    $msg  = $result['message'] ?? 'FAILED';
                    $code = $result['code'] ?? 'UNKNOWN';

                    $statusMsg   = "FAILED ($code): ".$msg;
                    $statusColor = "red";

                    echo formatlog($a->PASIEN_ID,$a->EPISODE_ID,$a->TGLKELUAR,$a->DOKTER_ID,$statusMsg,'cyan','cyan','cyan','cyan',$statusColor);
                }
            }
            
        }
        
    }
    
}