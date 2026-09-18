<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require APPPATH . 'libraries/REST_Controller.php';
    use Restserver\Libraries\REST_Controller;


    class NlpTesting extends REST_Controller{
        /**
         * ============================================================
         * INTENTS
         * ============================================================
         */

        private $intents = array(
            'GREETING',
            'BIAYA',
            'PENDAFTARAN',
            'BPJS',
            'ALAMAT',
            'KELUHAN',
            'JAM_BESUK'
        );


        /**
         * ============================================================
         * CONSTRUCTOR
         * ============================================================
         */

        public function __construct()
        {
            parent::__construct();

            /*
            * Helper NLP Anda
            */
            $this->load->helper('whatsapp');
        }


        /**
         * ============================================================
         * TEST SINGLE MESSAGE
         * ============================================================
         *
         * POST
         *
         * {
         *     "message": "berapa biaya berobat?"
         * }
         *
         * URL:
         *
         * /restapi/NlpTesting/test
         */

        public function test_post()
        {
            $message = trim(
                $this->post('message')
            );


            if ($message == '') {

                return $this->response(
                    array(
                        'success' => false,
                        'message' => 'Parameter message wajib diisi'
                    ),
                    REST_Controller::HTTP_BAD_REQUEST
                );
            }


            $result = $this->detectIntent(
                $message
            );


            return $this->response(
                array(
                    'success' => true,
                    'data' => $result
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * TEST DATASET
         * ============================================================
         *
         * GET
         *
         * /restapi/NlpTesting/dataset
         */

        public function dataset_get()
        {
            $dataset = $this->getDataset();

            $results = array();


            $no = 1;

            foreach ($dataset as $item) {

                $result = $this->detectIntent(
                    $item['text']
                );

                $results[] = array(

                    'no' => $no++,

                    'expected' =>
                        $item['intent'],

                    'text' =>
                        $item['text'],

                    'normalized' =>
                        $result['normalized'],

                    'detected' =>
                        $result['detected'],

                    'primary_intent' =>
                        $result['primary_intent'],

                    'multiple_intent' =>
                        $result['multiple_intent'],

                    'status' =>
                        (
                            $item['intent']
                            ==
                            $result['primary_intent']
                        )
                        ? 'MATCH'
                        : 'MISS'
                );
            }


            return $this->response(
                array(
                    'success' => true,
                    'total' => count($results),
                    'data' => $results
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * EVALUATE
         * ============================================================
         *
         * GET
         *
         * /restapi/NlpTesting/evaluate
         */

        public function evaluate_get()
        {
            $evaluation =
                $this->runEvaluation();


            return $this->response(
                array(
                    'success' => true,

                    'summary' =>
                        $evaluation['summary'],

                    'metrics' =>
                        $evaluation['metrics'],

                    'confusion_matrix' =>
                        $evaluation['confusion_matrix'],

                    'miss_total' =>
                        count($evaluation['miss']),

                    'multi_intent_total' =>
                        count($evaluation['multi_intent'])
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * MISS
         * ============================================================
         *
         * GET
         *
         * /restapi/NlpTesting/miss
         */

        public function miss_get()
        {
            $evaluation =
                $this->runEvaluation();


            return $this->response(
                array(
                    'success' => true,

                    'total' =>
                        count($evaluation['miss']),

                    'data' =>
                        $evaluation['miss']
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * MULTI INTENT
         * ============================================================
         *
         * GET
         *
         * /restapi/NlpTesting/multi
         */

        public function multi_get()
        {
            $evaluation =
                $this->runEvaluation();


            return $this->response(
                array(
                    'success' => true,

                    'total' =>
                        count($evaluation['multi_intent']),

                    'data' =>
                        $evaluation['multi_intent']
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * CONFUSION MATRIX
         * ============================================================
         *
         * GET
         *
         * /restapi/NlpTesting/confusion
         */

        public function confusion_get()
        {
            $evaluation =
                $this->runEvaluation();


            return $this->response(
                array(
                    'success' => true,

                    'matrix' =>
                        $evaluation['confusion_matrix']
                ),
                REST_Controller::HTTP_OK
            );
        }


        /**
         * ============================================================
         * DETECT INTENT
         * ============================================================
         */

        private function detectIntent($message)
        {
            $normalized =
                normalizeNLP($message);


            $detected = array();


            /**
             * GREETING
             */
            if (isGreeting($message)) {

                $detected[] = 'GREETING';
            }


            /**
             * BIAYA
             */
            if (isBiaya($message)) {

                $detected[] = 'BIAYA';
            }


            /**
             * PENDAFTARAN
             */
            if (isPendaftaran($message)) {

                $detected[] = 'PENDAFTARAN';
            }


            /**
             * BPJS
             */
            if (isBPJS($message)) {

                $detected[] = 'BPJS';
            }


            /**
             * ALAMAT
             */
            if (isAlamat($message)) {

                $detected[] = 'ALAMAT';
            }


            /**
             * KELUHAN
             */
            if (isKeluhan($message)) {

                $detected[] = 'KELUHAN';
            }


            /**
             * JAM BESUK
             */
            if (isJamBesuk($message)) {

                $detected[] = 'JAM_BESUK';
            }


            /**
             * ========================================================
             * PRIMARY INTENT
             * ========================================================
             *
             * Intent yang lebih spesifik diprioritaskan
             * dibanding GREETING.
             */

            $priority = array(

                'KELUHAN',

                'BPJS',

                'JAM_BESUK',

                'ALAMAT',

                'BIAYA',

                'PENDAFTARAN',

                'GREETING'
            );


            $primaryIntent = 'UNKNOWN';


            foreach ($priority as $intent) {

                if (
                    in_array(
                        $intent,
                        $detected
                    )
                ) {

                    $primaryIntent =
                        $intent;

                    break;
                }
            }


            return array(

                'message' =>
                    $message,

                'normalized' =>
                    $normalized,

                'detected' =>
                    $detected,

                'primary_intent' =>
                    $primaryIntent,

                'multiple_intent' =>
                    count($detected) > 1,

                'total_intent' =>
                    count($detected)
            );
        }


        /**
         * ============================================================
         * RUN EVALUATION
         * ============================================================
         */

        private function runEvaluation()
        {
            $dataset =
                $this->getDataset();


            /**
             * Confusion Matrix
             */

            $confusion = array();


            foreach ($this->intents as $expected) {

                $confusion[$expected] =
                    array();

                foreach ($this->intents as $predicted) {

                    $confusion[$expected][$predicted] =
                        0;
                }

                $confusion[$expected]['UNKNOWN'] =
                    0;
            }


            $match = 0;

            $miss = 0;

            $results = array();

            $missResults = array();

            $multiResults = array();


            /**
             * ========================================================
             * RUN DATASET
             * ========================================================
             */

            $no = 1;

            foreach ($dataset as $item) {

                $result =
                    $this->detectIntent(
                        $item['text']
                    );


                $expected =
                    $item['intent'];


                $predicted =
                    $result['primary_intent'];


                if (
                    isset(
                        $confusion[$expected][$predicted]
                    )
                ) {

                    $confusion[$expected][$predicted]++;
                }


                $status =
                    (
                        $expected ==
                        $predicted
                    )
                    ? 'MATCH'
                    : 'MISS';


                if ($status == 'MATCH') {

                    $match++;

                } else {

                    $miss++;
                }


                $row = array(

                    'no' =>
                        $no++,

                    'expected' =>
                        $expected,

                    'text' =>
                        $item['text'],

                    'detected' =>
                        $result['detected'],

                    'predicted' =>
                        $predicted,

                    'multiple_intent' =>
                        $result['multiple_intent'],

                    'status' =>
                        $status
                );


                $results[] =
                    $row;


                if ($status == 'MISS') {

                    $missResults[] =
                        $row;
                }


                if (
                    $result['multiple_intent']
                ) {

                    $multiResults[] =
                        $row;
                }
            }


            /**
             * ========================================================
             * METRICS
             * ========================================================
             */

            $metrics =
                $this->calculateMetrics(
                    $confusion,
                    count($dataset)
                );


            return array(

                'summary' => array(

                    'total' =>
                        count($dataset),

                    'match' =>
                        $match,

                    'miss' =>
                        $miss,

                    'accuracy' =>
                        $this->percentage(
                            $match,
                            count($dataset)
                        )
                ),

                'metrics' =>
                    $metrics,

                'confusion_matrix' =>
                    $confusion,

                'results' =>
                    $results,

                'miss' =>
                    $missResults,

                'multi_intent' =>
                    $multiResults
            );
        }


        /**
         * ============================================================
         * METRICS
         * ============================================================
         */

        private function calculateMetrics(
            $matrix,
            $total
        ) {
            $metrics = array();


            foreach ($this->intents as $intent) {

                $TP = 0;

                $FP = 0;

                $FN = 0;

                $TN = 0;


                /**
                 * TP
                 */

                if (
                    isset(
                        $matrix[$intent][$intent]
                    )
                ) {

                    $TP =
                        $matrix[$intent][$intent];
                }


                /**
                 * FP
                 */

                foreach (
                    $matrix
                    as $expected => $prediction
                ) {

                    if (
                        $expected ==
                        $intent
                    ) {

                        continue;
                    }


                    if (
                        isset(
                            $prediction[$intent]
                        )
                    ) {

                        $FP +=
                            $prediction[$intent];
                    }
                }


                /**
                 * FN
                 */

                if (
                    isset(
                        $matrix[$intent]
                    )
                ) {

                    foreach (
                        $matrix[$intent]
                        as $predicted => $value
                    ) {

                        if (
                            $predicted ==
                            $intent
                        ) {

                            continue;
                        }


                        $FN +=
                            $value;
                    }
                }


                /**
                 * TN
                 */

                $TN =
                    $total -
                    $TP -
                    $FP -
                    $FN;


                /**
                 * Precision
                 */

                $precision = 0;

                if (
                    ($TP + $FP) > 0
                ) {

                    $precision =
                        $TP /
                        ($TP + $FP);
                }


                /**
                 * Recall
                 */

                $recall = 0;

                if (
                    ($TP + $FN) > 0
                ) {

                    $recall =
                        $TP /
                        ($TP + $FN);
                }


                /**
                 * F1
                 */

                $f1 = 0;

                if (
                    ($precision + $recall) > 0
                ) {

                    $f1 =
                        (
                            2 *
                            $precision *
                            $recall
                        )
                        /
                        (
                            $precision +
                            $recall
                        );
                }


                $metrics[$intent] = array(

                    'TP' =>
                        $TP,

                    'FP' =>
                        $FP,

                    'FN' =>
                        $FN,

                    'TN' =>
                        $TN,

                    'precision' =>
                        round(
                            $precision * 100,
                            2
                        ),

                    'recall' =>
                        round(
                            $recall * 100,
                            2
                        ),

                    'f1' =>
                        round(
                            $f1 * 100,
                            2
                        )
                );
            }


            return $metrics;
        }


        /**
         * ============================================================
         * PERCENTAGE
         * ============================================================
         */

        private function percentage(
            $value,
            $total
        ) {
            if ($total <= 0) {

                return 0;
            }


            return round(
                (
                    $value /
                    $total
                ) * 100,
                2
            );
        }

        private function getDataset()
        {
            return array(

                /**
                 * ====================================================
                 * GREETING
                 * ====================================================
                 */

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo kak, saya mau tanya dong'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Pagi kak, mau minta info dong'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Permisi kak, boleh tanya sesuatu?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Hai min, saya mau cari informasi'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo admin, mau tanya soal rumah sakit'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Selamat pagi kak, saya mau tanya'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Malam min, boleh minta informasinya?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Siang kak, saya mau bertanya mengenai pelayanan di RS'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo, bisa bantu saya kak?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Permisi, saya mau menanyakan beberapa informasi'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Hai kak, boleh dibantu?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo min, saya ingin mendapatkan informasi'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Pagi admin, boleh saya tanya?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo kak, saya ada yang mau ditanyakan'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Misi kak, mau tanya sebentar'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Assalamualaikum kak, saya mau tanya tentang RSUD'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo admin RSUD Pasar Minggu, bisa bantu saya?'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Selamat siang, saya ingin bertanya mengenai layanan rumah sakit'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Halo kak, saya baru pertama kali mau ke RSUD Pasar Minggu'
                ),

                array(
                    'intent' => 'GREETING',
                    'text' => 'Permisi kak, saya ingin mencari informasi tentang pelayanan di sini'
                ),


                /**
                 * ====================================================
                 * BIAYA
                 * ====================================================
                 */

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Berapa biaya berobat di RSUD Pasar Minggu?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kalau mau periksa dokter kena biaya berapa ya kak?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Mau tanya biaya pemeriksaan dokter dong'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Untuk berobat ke poli umum habis berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kalau saya mau kontrol, biayanya berapa ya?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Biaya daftar dan pemeriksaan di sini berapa kak?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kak, kalau pasien umum bayar berapa untuk sekali periksa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Saya mau berobat, kira-kira siapin uang berapa ya?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Untuk pemeriksaan dokter tarifnya berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kalau bukan BPJS, biaya berobatnya berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Ada info harga pemeriksaan dokter nggak kak?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Mau tanya dong, periksa ke RS ini bayar berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kalau daftar langsung ke rumah sakit ada biaya pendaftarannya nggak?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kak kalau saya pasien umum, sekali datang kira-kira habis berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Tarif konsultasi dokter berapa ya?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Untuk berobat ke poli, apakah ada biaya pemeriksaan?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Saya mau periksa dokter, biaya awalnya berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Berapa ya kak biaya pendaftaran sama pemeriksaan dokternya?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kalau mau berobat tanpa BPJS apakah bisa? Bayarnya berapa?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'Kak, boleh info kisaran biaya kalau mau periksa dokter di RSUD Pasar Minggu?'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'mau berobat harus bawa uang berapa'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'kalau bayar sendiri gimana kak'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'pasien umum tarifnya berapa'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'saya nggak pakai bpjs, kena berapa'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'periksa dokter bayar berapa ya'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'berapa yang harus saya bayar kalau konsultasi'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'ada tarif dokter nggak'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'harga sekali periksa berapa'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'kalau datang langsung bayar nggak'
                ),

                array(
                    'intent' => 'BIAYA',
                    'text' => 'estimasi biaya berobat berapa ya'
                ),


                /**
                 * ====================================================
                 * PENDAFTARAN
                 * ====================================================
                 */

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'mau daftar berobat gimana ya'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kalau mau daftar ke poli caranya gimana kak'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'saya mau daftar berobat'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'pendaftaran pasien baru gimana ya'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'bisa daftar lewat whatsapp nggak'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kalau mau bikin janji dengan dokter bagaimana'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'daftar online bisa nggak kak'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'saya mau daftar untuk periksa dokter'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'cara daftar ke RSUD Pasar Minggu gimana'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kalau mau berobat harus daftar dulu ya'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'bisa langsung datang atau harus daftar online'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'pendaftaran online lewat mana ya'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'saya belum pernah berobat di sini, cara daftarnya gimana'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'pasien baru bisa daftar online nggak'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'mau ambil nomor antrean gimana ya'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kalau mau daftar poli harus lewat aplikasi apa'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'saya mau bikin jadwal dengan dokter'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'pendaftaran untuk berobat dibuka sampai jam berapa'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kalau sudah punya jadwal dokter, cara daftarnya bagaimana'
                ),

                array(
                    'intent' => 'PENDAFTARAN',
                    'text' => 'kak, saya mau berobat besok, bisa dibantu cara pendaftarannya?'
                ),


                /**
                 * ====================================================
                 * BPJS
                 * ====================================================
                 */

                array(
                    'intent' => 'BPJS',
                    'text' => 'saya mau berobat pakai bpjs'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kalau pakai bpjs harus bawa surat rujukan nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'bisa berobat menggunakan bpjs di sini'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kak, kalau pasien bpjs cara daftarnya gimana'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'saya peserta bpjs, bisa berobat ke poli nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'untuk bpjs harus ada rujukan dari faskes 1 ya'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kalau mau kontrol pakai bpjs perlu surat rujukan lagi nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'bpjs saya bisa digunakan di RSUD Pasar Minggu nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'cara berobat menggunakan bpjs bagaimana ya'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kalau pakai bpjs apakah gratis'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'saya punya bpjs tapi belum pernah berobat di sini, gimana caranya'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'peserta bpjs bisa daftar online nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kalau kartu bpjs aktif bisa langsung datang ke rumah sakit?'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'untuk pasien bpjs harus daftar dulu atau bisa langsung ke poli'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'saya mau pakai bpjs untuk periksa dokter'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'rujukan bpjs dari puskesmas bisa digunakan di sini nggak'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kalau tidak punya rujukan apakah tetap bisa pakai bpjs'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'bpjs untuk pemeriksaan dokter apakah ditanggung'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'saya mau berobat pakai bpjs, dokumen apa saja yang harus dibawa'
                ),

                array(
                    'intent' => 'BPJS',
                    'text' => 'kak, kalau keadaan darurat pakai bpjs bisa langsung ke IGD nggak'
                ),


                /**
                 * ====================================================
                 * ALAMAT
                 * ====================================================
                 */

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'alamat RSUD Pasar Minggu di mana ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'RSUD Pasar Minggu lokasinya di mana kak'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'boleh minta alamat rumah sakitnya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'rumah sakitnya ada di jalan apa ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'RSUD Pasar Minggu tepatnya di mana'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'kalau mau ke rumah sakit alamat lengkapnya apa'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'kak, lokasi RSUD Pasar Minggu di mana ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'saya mau datang ke rumah sakit, alamatnya di mana'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'bisa kasih alamat RSUD Pasar Minggu'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'rumah sakitnya dekat mana ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'patokannya di mana kak'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'kalau dari jalan raya masuknya lewat mana'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'lokasi rumah sakitnya sebelah mana ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'saya baru pertama kali ke sana, alamat lengkapnya boleh minta'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'RSUD Pasar Minggu itu di daerah mana'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'boleh share lokasi rumah sakitnya kak'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'minta shareloc RSUD Pasar Minggu dong'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'kak kirim lokasi rumah sakitnya ya'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'saya mau ke RSUD Pasar Minggu, maps-nya ada nggak'
                ),

                array(
                    'intent' => 'ALAMAT',
                    'text' => 'alamat lengkap RSUD Pasar Minggu apa ya kak'
                ),


                /**
                 * ====================================================
                 * KELUHAN
                 * ====================================================
                 */

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya mau menyampaikan keluhan'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya mau komplain soal pelayanan'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'mau lapor keluhan kak'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya ada keluhan tentang pelayanan di rumah sakit'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'bagaimana cara menyampaikan pengaduan'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya ingin mengadukan pelayanan yang saya terima'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'mau menyampaikan komplain lewat sini bisa nggak'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya kurang puas dengan pelayanan di rumah sakit'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'kak, saya mau lapor masalah yang saya alami'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya ingin membuat pengaduan'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'kalau mau komplain harus ke mana ya'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya mau melaporkan kejadian yang saya alami'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'ada bagian pengaduan pasien nggak'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya ingin menyampaikan ketidaknyamanan selama pelayanan'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'mau lapor terkait pelayanan dokter'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya punya masalah dengan pelayanan rumah sakit'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya mau menyampaikan kritik dan saran'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'bagaimana prosedur pengaduan pasien'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'kak, saya mau mengajukan komplain'
                ),

                array(
                    'intent' => 'KELUHAN',
                    'text' => 'saya ingin melaporkan keluhan saya terkait pelayanan di RSUD'
                ),


                /**
                 * ====================================================
                 * JAM BESUK
                 * ====================================================
                 */

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam besuk di rumah sakit ini mulai jam berapa ya'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'kalau mau besuk pasien boleh datang jam berapa'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam kunjungan pasien sampai jam berapa kak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'besuk pasien boleh jam berapa saja'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam besuk RSUD Pasar Minggu kapan ya'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'kalau sore boleh besuk nggak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'untuk kunjungan pasien ada jam tertentu nggak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'saya mau jenguk keluarga, bisa datang jam berapa'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'besuk pasien mulai jam berapa kak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam kunjungan pasien hari ini sampai jam berapa'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'kalau mau menjenguk pasien malam hari boleh nggak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'apakah ada batas waktu untuk besuk pasien'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'keluarga pasien boleh berkunjung jam berapa'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam besuk untuk pasien rawat inap kapan ya'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'saya mau menjenguk teman yang dirawat, jam besuknya kapan'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'kalau datang pagi boleh masuk untuk besuk pasien nggak'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'jam besuk hari sabtu dan minggu apakah sama'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'aturan jam kunjungan pasien bagaimana ya'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'kak, kalau mau besuk pasien harus datang sesuai jam kunjungan ya'
                ),

                array(
                    'intent' => 'JAM_BESUK',
                    'text' => 'boleh minta info jadwal jam besuk RSUD Pasar Minggu?'
                )
            );


            /**
             * Tambahkan nomor otomatis
             */

            $no = 1;

            foreach ($dataset as &$item) {

                $item['no'] = $no++;
            }

            unset($item);


            return $dataset;
        }
    }
?>