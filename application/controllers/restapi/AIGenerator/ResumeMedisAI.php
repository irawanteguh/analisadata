<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require 'vendor/autoload.php';

class ResumeMedisAI extends REST_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("ModelResumeMedisAI","md");
    }

    public function generateresumeai_post(){
        $id_kunjungan = $this->post('id_kunjungan');

        // if(empty($id_kunjungan)){
        //     return $this->response([
        //         'status' => false,
        //         'message' => 'ID Kunjungan tidak boleh kosong'
        //     ], 400);
        // }

        $dataklinis = $this->md->riwayatpenyakitdahulu($id_kunjungan);

        if(empty($dataklinis)){
            $datatext = "Tidak dicatat";
        } else {

            $rows = [];

            foreach($dataklinis as $row){

                // Karena result_array() → pakai []
                $value = isset($row['RESULTSOAP']) ? $row['RESULTSOAP'] : '';

                $value = strip_tags($value);
                $value = trim($value);

                if(!empty($value)){
                    $rows[] = $value;
                }
            }

            $datatext = empty($rows) ? "Tidak dicatat" : "- " . implode("\n- ", $rows);
        }

        $prompt = "
                    TUGAS:
                    Ubah DATA KLINIS menjadi narasi Riwayat Penyakit Dahulu.

                    ATURAN WAJIB:
                    - Gunakan HANYA teks yang ada di DATA KLINIS.
                    - DILARANG menambahkan judul apa pun.
                    - DILARANG membuat bullet, daftar, simbol *, atau tanda -.
                    - DILARANG mengulang kalimat yang sama.
                    - DILARANG membuat interpretasi seperti 'memiliki riwayat (+)'.
                    - DILARANG menambahkan kalimat pembuka atau penutup.
                    - Jangan membuat kesimpulan tambahan.
                    - Jika DATA KLINIS kosong tulis tepat: Tidak dicatat.

                    FORMAT:
                    - Satu paragraf saja.
                    - Tanpa baris baru.
                    - Tanpa simbol atau tanda daftar.
                    - Bahasa Indonesia medis formal.

                    DATA KLINIS:
                    {$datatext}
                    ";

        // return var_dump($prompt);

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

        $ch = curl_init("http://localhost:11434/api/generate");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            return $this->response([
                'status' => false,
                'message' => 'Gagal menghubungi AI Server: ' . curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if(!isset($result['response'])){
            return $this->response([
                'status' => false,
                'message' => 'Response AI tidak valid'
            ], 500);
        }

        return $this->response([
            'status' => true,
            'resume' => trim($result['response'])
        ], 200);
    }
}