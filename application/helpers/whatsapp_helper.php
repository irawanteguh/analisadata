<?php
    function headerlogwhatsapp(){
        echo PHP_EOL;
        echo color('cyan'). str_pad("TIMESTAMP", 21). str_pad("MESSAGE ID", 49). str_pad("STATUS", 12). "MESSAGE". PHP_EOL;
    }

    function formatlogwhatsapp($tanggal, $messageid, $status, $message, $colortanggal = 'cyan', $colormessageid = 'cyan', $colorstatus = 'cyan', $colorMessage = 'white') {

        $tanggalWidth    = 21;
        $messageidWidth  = 49;
        $statusWidth     = 12;

        $colorStartTanggal   = color($colortanggal);
        $colorStartMessageId = color($colormessageid);
        $colorStartStatus    = color($colorstatus);
        $colorStartMessage   = color($colorMessage);

        $reset = color('reset');

        $formatted  = $colorStartTanggal . str_pad($tanggal, $tanggalWidth) . $reset;
        $formatted .= $colorStartMessageId . str_pad($messageid, $messageidWidth) . $reset;
        $formatted .= $colorStartStatus . str_pad($status, $statusWidth) . $reset;
        $formatted .= $colorStartMessage . $message . $reset;

        return $formatted . PHP_EOL;
    }

    function normalizeNLP($message)
    {
        $text = trim($message);

        if ($text == '') {
            return '';
        }

        $text = strtoupper($text);

        /*
        |--------------------------------------------------------------------------
        | Normalisasi typo / bahasa percakapan
        |--------------------------------------------------------------------------
        */
        $replace = array(
            'BEZUK'                    => 'BESUK',
            'JENGUK'                  => 'BESUK',
            'MENJENGUK'               => 'BESUK',
            'KUNJUNGAN'               => 'KUNJUNG',
            'KUNJUNGIN'               => 'KUNJUNG',

            'PAKE'                    => 'PAKAI',

            'NGGAK'                   => 'TIDAK',
            'NGGA'                    => 'TIDAK',
            'GAK'                    => 'TIDAK',
            'GA'                     => 'TIDAK',

            'DIMANA'                  => 'DI MANA',
            'DMN'                     => 'DI MANA',

            'RS PASAR MINGGU'         => 'RSUD PASAR MINGGU',
            'RUMAH SAKIT PASAR MINGGU' => 'RSUD PASAR MINGGU',

            'SHARELOC'                => 'SHARE LOKASI',
            'SHARELOCK'               => 'SHARE LOKASI'
        );

        /*
        |--------------------------------------------------------------------------
        | Replace menggunakan word boundary
        |--------------------------------------------------------------------------
        | Menghindari kasus "GA" mengganti bagian kata lain.
        |--------------------------------------------------------------------------
        */
        foreach ($replace as $from => $to) {

            $pattern = '/\b' . preg_quote($from, '/') . '\b/u';

            $text = preg_replace(
                $pattern,
                $to,
                $text
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hilangkan punctuation
        |--------------------------------------------------------------------------
        */
        $text = preg_replace(
            '/[^\p{L}\p{N}\s]/u',
            ' ',
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | Normalisasi spasi
        |--------------------------------------------------------------------------
        */
        $text = preg_replace(
            '/\s+/u',
            ' ',
            $text
        );

        return trim($text);
    }

    function hasKeyword($text, $keywords)
    {
        foreach($keywords as $keyword){

            $keyword = strtoupper(trim($keyword));

            if($keyword == ''){
                continue;
            }

            $pattern = '/\b'.preg_quote($keyword, '/').'\b/u';

            if(preg_match($pattern, $text)){
                return true;
            }
        }

        return false;
    }

    function hasPhrase($text, $phrases)
    {
        foreach($phrases as $phrase){

            $phrase = strtoupper(trim($phrase));

            if($phrase == ''){
                continue;
            }

            if(strpos($text, $phrase) !== false){
                return true;
            }
        }

        return false;
    }
    
    function isGreeting($message)
    {
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $greeting = array(
            'HALO',
            'HALLO',
            'HELLO',
            'HAI',
            'HI',
            'HEI',
            'HEY',
            'HAY',
            'HII',
            'HIII',
            'HY',
            'PAGI',
            'SIANG',
            'SORE',
            'MALAM',
            'ASSALAMUALAIKUM',
            'ASS WR WB',
            'PERMISI',
            'MISI'
        );

        $context = array(
            'ADMIN',
            'MIN',
            'RSUD',
            'RS',
            'PEO'
        );

        $question = array(
            'MAU TANYA',
            'MAU BERTANYA',
            'SAYA MAU TANYA',
            'SAYA MAU BERTANYA',
            'MINTA INFORMASI',
            'BUTUH INFORMASI'
        );

        // Greeting langsung
        if(hasKeyword($text, $greeting)){
            return true;
        }

        // Greeting + konteks
        if(
            hasKeyword($text, $greeting) &&
            hasKeyword($text, $context)
        ){
            return true;
        }

        // Pembuka percakapan
        foreach($question as $pattern){
            if(strpos($text, $pattern) !== false){
                return true;
            }
        }

        return false;
    }

    function isJamBesuk($message){
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $score = 0;


        /*
        * =========================================================
        * 1. KATA UTAMA BESUK / KUNJUNGAN
        * =========================================================
        */
        $visit = array(
            'BESUK',
            'KUNJUNG',
            'PASIEN',
            'RAWAT INAP',
            'MENJENGUK'
        );

        if(hasKeyword($text, $visit)){
            $score += 3;
        }


        /*
        * =========================================================
        * 2. KATA TERKAIT WAKTU
        * =========================================================
        *
        * PAGI / SIANG / SORE / MALAM penting karena user
        * sering bertanya:
        *
        * "boleh besuk sore?"
        * "besuk malam boleh?"
        * "pagi boleh menjenguk?"
        */
        $time = array(
            'JAM',
            'WAKTU',
            'JADWAL',
            'KAPAN',
            'PUKUL',
            'PAGI',
            'SIANG',
            'SORE',
            'MALAM'
        );


        /*
        * =========================================================
        * 3. PHRASE KUAT
        * =========================================================
        */
        $strongPhrase = array(
            'JAM BESUK',
            'JADWAL BESUK',
            'WAKTU BESUK',
            'JAM KUNJUNG',
            'JADWAL KUNJUNG',
            'WAKTU KUNJUNG',
            'WAKTU MENJENGUK',
            'JADWAL MENJENGUK',
            'JAM MENJENGUK',
            'BOLEH BESUK',
            'BISA BESUK',
            'BOLEH MENJENGUK',
            'BISA MENJENGUK'
        );

        if(hasPhrase($text, $strongPhrase)){
            return true;
        }


        /*
        * =========================================================
        * 4. BESUK + WAKTU
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'BESUK',
                'KUNJUNG',
                'MENJENGUK'
            ))
            &&
            hasKeyword($text, $time)
        ){
            return true;
        }


        /*
        * =========================================================
        * 5. BESUK + PASIEN / RAWAT INAP
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'BESUK',
                'KUNJUNG',
                'MENJENGUK'
            ))
            &&
            hasKeyword($text, array(
                'PASIEN',
                'RAWAT INAP'
            ))
        ){
            return true;
        }


        /*
        * =========================================================
        * 6. SKOR MINIMAL
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'BESUK',
                'KUNJUNG',
                'MENJENGUK'
            ))
        ){
            $score += 2;
        }

        if(hasKeyword($text, $time)){
            $score += 2;
        }

        return ($score >= 5);
    }

    function isAlamat($message){
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $score = 0;


        /*
        * =========================================================
        * 1. KATA UTAMA LOKASI
        * =========================================================
        */
        $location = array(
            'ALAMAT',
            'LOKASI',
            'DIMANA',
            'DI MANA',
            'LETAK',
            'POSISI',
            'JALAN',
            'PATOKAN',
            'MAP',
            'MAPS'
        );

        if(hasKeyword($text, $location)){
            $score += 5;
        }


        /*
        * =========================================================
        * 2. KONTEKS ARAH / LOKASI
        * =========================================================
        */
        $direction = array(
            'DEKAT MANA',
            'SEBELAH MANA',
            'MASUKNYA',
            'LEWAT MANA',
            'DARI JALAN RAYA',
            'JALAN APA',
            'DI DAERAH MANA',
            'ARAH MANA'
        );

        if(hasPhrase($text, $direction)){
            $score += 5;
        }


        /*
        * =========================================================
        * 3. SHARE / KIRIM LOKASI
        * =========================================================
        */
        $shareLocation = array(
            'SHARE LOKASI',
            'SHARELOC',
            'SHARE LOC',
            'KIRIM LOKASI',
            'KIRIM SHARELOC',
            'KIRIM SHARE LOC',
            'BAGI LOKASI',
            'BAGIKAN LOKASI',
            'SHARE MAPS',
            'KIRIM MAPS'
        );

        if(hasPhrase($text, $shareLocation)){
            $score += 7;
        }


        /*
        * =========================================================
        * 4. KONTEKS RUMAH SAKIT
        * =========================================================
        */
        $hospital = array(
            'RSUD',
            'RUMAH SAKIT',
            'RS',
            'RUMAH SAKITNYA',
            'RSUD PASAR MINGGU',
            'PASAR MINGGU'
        );

        if(hasKeyword($text, $hospital)){
            $score += 2;
        }


        /*
        * =========================================================
        * 5. POLA ALAMAT LENGKAP
        * =========================================================
        */
        $addressPhrase = array(
            'ALAMAT LENGKAP',
            'MINTA ALAMAT',
            'BOLEH MINTA ALAMAT',
            'ALAMAT RUMAH SAKIT',
            'ALAMAT RUMAH SAKITNYA',
            'LOKASI RUMAH SAKIT',
            'LOKASI RUMAH SAKITNYA'
        );

        if(hasPhrase($text, $addressPhrase)){
            $score += 7;
        }


        /*
        * =========================================================
        * 6. POLA PATOKAN
        * =========================================================
        */
        $landmark = array(
            'PATOKAN',
            'PATOKANNYA',
            'DEKAT MANA',
            'SEBELAH MANA',
            'DI DAERAH MANA',
            'JALAN APA'
        );

        if(hasPhrase($text, $landmark)){
            $score += 5;
        }


        /*
        * =========================================================
        * 7. POLA ARAH MASUK
        * =========================================================
        */
        if(
            hasKeyword($text, array('MASUKNYA')) ||
            hasPhrase($text, array(
                'LEWAT MANA',
                'DARI JALAN RAYA'
            ))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 8. RSUD PASAR MINGGU + LOKASI
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'RSUD PASAR MINGGU',
                'PASAR MINGGU'
            )) &&
            (
                hasKeyword($text, $location) ||
                hasPhrase($text, $direction)
            )
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 9. MINIMUM SCORE
        * =========================================================
        */
        return ($score >= 5);
    }

    function isPendaftaran($message){
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $score = 0;


        /*
        * =========================================================
        * 1. KATA UTAMA PENDAFTARAN
        * =========================================================
        */
        $registration = array(
            'DAFTAR',
            'PENDAFTARAN',
            'MENDAFTAR',
            'REGISTRASI'
        );

        if(hasKeyword($text, $registration)){
            $score += 5;
        }


        /*
        * =========================================================
        * 2. KONTEKS JANJI / BOOKING
        * =========================================================
        */
        $booking = array(
            'JANJI',
            'JANJI TEMU',
            'BOOKING',
            'RESERVASI',
            'JADWAL DOKTER'
        );

        if(hasKeyword($text, $booking)){
            $score += 5;
        }


        /*
        * =========================================================
        * 3. KONTEKS ANTREAN
        * =========================================================
        */
        $queue = array(
            'ANTREAN',
            'ANTRIAN',
            'NOMOR ANTREAN',
            'NOMOR ANTRIAN',
            'AMBIL NOMOR',
            'AMBIL ANTREAN',
            'AMBIL ANTRIAN'
        );

        if(hasKeyword($text, $queue)){
            $score += 5;
        }


        /*
        * =========================================================
        * 4. PASIEN BARU
        * =========================================================
        */
        $newPatient = array(
            'PASIEN BARU',
            'BELUM PERNAH BEROBAT',
            'PERTAMA KALI BEROBAT',
            'PERTAMA KALI KE SANA',
            'PERTAMA KALI KE SINI'
        );

        if(hasPhrase($text, $newPatient)){
            $score += 5;
        }


        /*
        * =========================================================
        * 5. KONTEKS BEROBAT
        * =========================================================
        */
        if(
            hasKeyword($text, array('BEROBAT')) &&
            (
                hasKeyword($text, array('CARA')) ||
                hasKeyword($text, array('BAGAIMANA')) ||
                hasKeyword($text, array('DAFTAR', 'PENDAFTARAN'))
            )
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 6. CARA DAFTAR
        * =========================================================
        */
        $caraDaftar = array(
            'CARA DAFTAR',
            'CARA PENDAFTARAN',
            'BAGAIMANA CARA DAFTAR',
            'BAGAIMANA CARA PENDAFTARAN',
            'CARA MENDAFTAR',
            'CARA REGISTRASI'
        );

        if(hasPhrase($text, $caraDaftar)){
            $score += 5;
        }


        /*
        * =========================================================
        * 7. JANJI DENGAN DOKTER
        * =========================================================
        */
        $doctorBooking = array(
            'BIKIN JANJI',
            'BUAT JANJI',
            'BISA BOOKING',
            'CARA BOOKING',
            'JANJI DENGAN DOKTER',
            'JANJI TEMU DOKTER',
            'BUAT JANJI DOKTER',
            'BIKIN JANJI DOKTER',
            'JADWAL DENGAN DOKTER',
            'BIKIN JADWAL DOKTER'
        );

        if(hasPhrase($text, $doctorBooking)){
            $score += 5;
        }


        /*
        * =========================================================
        * 8. BEROBAT + CARA / BAGAIMANA
        * =========================================================
        */
        if(
            hasKeyword($text, array('BEROBAT')) &&
            hasKeyword($text, array(
                'CARA',
                'BAGAIMANA',
                'BISA'
            ))
        ){
            $score += 4;
        }


        /*
        * =========================================================
        * 9. JADWAL DOKTER
        * =========================================================
        */
        if(
            hasKeyword($text, array('JADWAL')) &&
            hasKeyword($text, array('DOKTER'))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 10. MINIMUM SCORE
        * =========================================================
        */
        return ($score >= 5);
    }

    function isBPJS($message)
    {
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $bpjs = array(
            'BPJS',
            'KIS',
            'JKN',
            'PESERTA BPJS',
            'KARTU BPJS'
        );

        $rujukan = array(
            'RUJUKAN',
            'SURAT RUJUKAN',
            'FASKES',
            'FASKES 1',
            'FASKES SATU',
            'FKTP'
        );

        $bpjsQuestion = array(
            'PAKAI BPJS',
            'PAKE BPJS',
            'BISA BPJS',
            'GUNAKAN BPJS',
            'MENGGUNAKAN BPJS',
            'DENGAN BPJS',
            'KALAU BPJS'
        );

        // BPJS eksplisit
        if(hasKeyword($text, $bpjs)){
            return true;
        }

        // Pertanyaan BPJS
        foreach($bpjsQuestion as $pattern){
            if(strpos($text, $pattern) !== false){
                return true;
            }
        }

        // Rujukan + konteks BPJS
        if(
            hasKeyword($text, $rujukan) &&
            (
                strpos($text, 'BPJS') !== false ||
                strpos($text, 'JKN') !== false ||
                strpos($text, 'KIS') !== false
            )
        ){
            return true;
        }

        return false;
    }

    function isKeluhan($message)
    {
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $score = 0;

        /*
        * =========================================================
        * 1. KEYWORD UTAMA KELUHAN
        * =========================================================
        */
        $keywords = array(
            'KELUHAN',
            'KOMPLAIN',
            'COMPLAIN',
            'PENGADUAN',
            'ADUAN',
            'MENGADU',
            'MENGADUKAN',
            'LAPOR',
            'MELAPORKAN'
        );

        foreach($keywords as $keyword){
            if(hasKeyword($text, array($keyword))){
                $score += 5;
            }
        }


        /*
        * =========================================================
        * 2. EKSPRESI KETIDAKPUASAN
        * =========================================================
        */
        $dissatisfaction = array(
            'KECEWA',
            'TIDAK PUAS',
            'KURANG PUAS',
            'TIDAK NYAMAN',
            'KURANG NYAMAN',
            'TIDAK SENANG',
            'KURANG SENANG',
            'MENGECEWAKAN',
            'KETIDAKNYAMANAN'
        );

        foreach($dissatisfaction as $keyword){
            if(hasKeyword($text, array($keyword))){
                $score += 4;
            }
        }


        /*
        * =========================================================
        * 3. PHRASE PENGADUAN
        * =========================================================
        */
        $phrases = array(
            'CARA MENYAMPAIKAN PENGADUAN',
            'CARA MENYAMPAIKAN KELUHAN',
            'CARA MENYAMPAIKAN KOMPLAIN',

            'PROSEDUR PENGADUAN',
            'PROSEDUR KELUHAN',

            'PENGADUAN PASIEN',
            'KELUHAN PASIEN',

            'MAU LAPOR',
            'INGIN LAPOR',
            'MAU MELAPORKAN',
            'INGIN MELAPORKAN',

            'MAU MENGADUKAN',
            'INGIN MENGADUKAN',

            'MEMBUAT PENGADUAN',
            'MEMBUAT KELUHAN',

            'MENYAMPAIKAN PENGADUAN',
            'MENYAMPAIKAN KELUHAN',
            'MENYAMPAIKAN KOMPLAIN',

            'ADA PENGADUAN',
            'ADA KELUHAN',

            'KRITIK DAN SARAN',

            'MENYAMPAIKAN KETIDAKNYAMANAN',
            'KETIDAKNYAMANAN SELAMA PELAYANAN',

            'MASALAH DENGAN PELAYANAN',
            'MASALAH PELAYANAN',

            'PENGADUAN TERKAIT PELAYANAN',
            'KELUHAN TERKAIT PELAYANAN',

            'TIDAK PUAS DENGAN PELAYANAN',
            'KURANG PUAS DENGAN PELAYANAN',

            'KECEWA DENGAN PELAYANAN'
        );

        if(hasPhrase($text, $phrases)){
            $score += 5;
        }


        /*
        * =========================================================
        * 4. KONTEKS PELAYANAN
        * =========================================================
        */
        $service = array(
            'PELAYANAN',
            'PETUGAS',
            'PERAWAT',
            'DOKTER',
            'ADMIN',
            'ADMINISTRASI',
            'PENDAFTARAN',
            'PENDAFTAR',
            'POLI',
            'POLIKLINIK',
            'IGD',
            'UGD',
            'RAWAT JALAN',
            'RAWAT INAP',
            'FARMASI',
            'APOTEK',
            'LAB',
            'LABORATORIUM',
            'RADIOLOGI',
            'KASIR',
            'LOKET',
            'SATPAM',
            'PERAWATAN',
            'RUMAH SAKIT'
        );

        if(hasKeyword($text, $service)){
            $score += 2;
        }


        /*
        * =========================================================
        * 5. NEGATIVE SERVICE EXPERIENCE
        * =========================================================
        */
        $negative = array(
            'LAMA',
            'LAMBAT',
            'MENUNGGU',
            'TIDAK DILAYANI',
            'TIDAK DITANGGAPI',
            'TIDAK ADA RESPON',
            'TIDAK JELAS',
            'TIDAK RAMAH',
            'KURANG RAMAH',
            'KASAR',
            'SOMBONG',
            'DIABAIKAN',
            'TERLAMBAT',
            'SALAH',
            'RIBET',
            'SULIT'
        );

        if(hasKeyword($text, $negative)){
            $score += 2;
        }


        /*
        * =========================================================
        * 6. MASALAH + PELAYANAN
        * =========================================================
        */
        if(
            hasKeyword($text, array('MASALAH')) &&
            hasKeyword($text, array('PELAYANAN'))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 7. KRITIK / SARAN
        * =========================================================
        */
        if(
            hasKeyword($text, array('KRITIK')) ||
            hasKeyword($text, array('SARAN'))
        ){
            if(
                hasKeyword($text, array(
                    'MENYAMPAIKAN',
                    'MAU',
                    'INGIN',
                    'MEMBERIKAN'
                ))
            ){
                $score += 5;
            }
        }


        /*
        * =========================================================
        * 8. KETIDAKPUASAN + PELAYANAN
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'KECEWA',
                'TIDAK PUAS',
                'KURANG PUAS',
                'TIDAK NYAMAN',
                'KURANG NYAMAN'
            ))
            &&
            hasKeyword($text, array(
                'PELAYANAN',
                'RUMAH SAKIT',
                'PETUGAS',
                'DOKTER',
                'PERAWAT'
            ))
        ){
            $score += 4;
        }


        /*
        * =========================================================
        * THRESHOLD
        * =========================================================
        */
        return ($score >= 5);
    }

    function isBiaya($message){
        $text = normalizeNLP($message);

        if($text == ''){
            return false;
        }

        $score = 0;

        /*
        * =========================================================
        * 1. KATA UTAMA BIAYA
        * =========================================================
        */
        $keywords = array(
            'BIAYA',
            'HARGA',
            'TARIF',
            'ONGKOS',
            'BAYAR',
            'PEMBAYARAN',
            'HARGANYA',
            'BIAYANYA',
            'TARIFNYA',
            'BAYARNYA',
            'KENA'
        );

        foreach($keywords as $keyword){

            if(hasKeyword($text, array($keyword))){

                if(in_array($keyword, array(
                    'BIAYA',
                    'HARGA',
                    'TARIF',
                    'BIAYANYA',
                    'HARGANYA',
                    'TARIFNYA'
                ))){

                    $score += 5;

                }else{

                    $score += 2;
                }
            }
        }


        /*
        * =========================================================
        * 2. KATA / KONTEKS JUMLAH UANG
        * =========================================================
        */
        $money = array(
            'BERAPA',
            'UANG',
            'KISARAN',
            'ESTIMASI',
            'SIAPIN',
            'SIAPKAN',
            'BAWA UANG',
            'SIAPIN UANG',
            'SIAPKAN UANG',
            'UANGNYA',
            'SEKALI DATANG',
            'SEKALI PERIKSA'
        );

        foreach($money as $keyword){

            if(hasKeyword($text, array($keyword))){

                if(in_array($keyword, array(
                    'BERAPA',
                    'UANG',
                    'KISARAN',
                    'ESTIMASI'
                ))){

                    $score += 2;

                }else{

                    $score += 3;
                }
            }
        }


        /*
        * =========================================================
        * 3. KONTEKS PELAYANAN / BEROBAT
        * =========================================================
        */
        $service = array(

            'BEROBAT',
            'PERIKSA',
            'PEMERIKSAAN',
            'DOKTER',
            'KONSULTASI',

            'PENDAFTARAN',
            'DAFTAR',
            'REGISTRASI',

            'POLI',
            'POLIKLINIK',

            'TINDAKAN',

            'RAWAT JALAN',
            'RAWAT INAP',

            'IGD',
            'UGD',

            'LAB',
            'LABORATORIUM',

            'RADIOLOGI',
            'USG',
            'RONTGEN',

            'FARMASI',
            'APOTEK',

            'OPERASI',
            'BEDAH',

            'PASIEN UMUM'
        );

        if(hasKeyword($text, $service)){
            $score += 2;
        }


        /*
        * =========================================================
        * 4. PHRASE KUAT BIAYA
        * =========================================================
        */
        $phrases = array(

            /*
            * Pertanyaan biaya umum
            */
            'BERAPA BIAYA',
            'BERAPA HARGA',
            'BERAPA TARIF',

            'BIAYA BERAPA',
            'HARGA BERAPA',
            'TARIF BERAPA',

            'BIAYANYA BERAPA',
            'HARGANYA BERAPA',
            'TARIFNYA BERAPA',

            'BAYAR BERAPA',
            'HARUS BAYAR BERAPA',
            'KENA BERAPA',

            'TOTAL BAYAR BERAPA',
            'TOTAL BIAYA BERAPA',

            'BERAPA YANG HARUS DIBAYAR',

            /*
            * Berobat
            */
            'BIAYA BEROBAT',
            'HARGA BEROBAT',
            'TARIF BEROBAT',

            'BEROBAT BERAPA',
            'BEROBAT HABIS BERAPA',

            /*
            * Pemeriksaan
            */
            'BIAYA PEMERIKSAAN',
            'HARGA PEMERIKSAAN',
            'TARIF PEMERIKSAAN',

            'PEMERIKSAAN BERAPA',

            /*
            * Dokter
            */
            'BIAYA DOKTER',
            'HARGA DOKTER',
            'TARIF DOKTER',

            'DOKTER BERAPA',

            /*
            * Konsultasi
            */
            'BIAYA KONSULTASI',
            'HARGA KONSULTASI',
            'TARIF KONSULTASI',

            'KONSULTASI BERAPA',

            /*
            * Pendaftaran
            */
            'BIAYA PENDAFTARAN',
            'HARGA PENDAFTARAN',
            'TARIF PENDAFTARAN',

            /*
            * Rawat jalan / inap
            */
            'BIAYA RAWAT JALAN',
            'BIAYA RAWAT INAP',

            /*
            * IGD
            */
            'BIAYA IGD',
            'BIAYA UGD',

            /*
            * Laboratorium
            */
            'BIAYA LAB',
            'BIAYA LABORATORIUM',

            /*
            * Radiologi
            */
            'BIAYA RADIOLOGI',
            'BIAYA USG',
            'BIAYA RONTGEN',

            /*
            * Tindakan
            */
            'BIAYA TINDAKAN',
            'HARGA TINDAKAN',

            /*
            * Bahasa natural
            */
            'SIAPIN UANG',
            'SIAPKAN UANG',
            'BAWA UANG',

            'UANG BERAPA',
            'SIAPIN BERAPA',
            'SIAPKAN BERAPA',

            'SEKALI DATANG',
            'SEKALI PERIKSA',

            'PASIEN UMUM',
            'PASIENT UMUM'
        );

        if(hasPhrase($text, $phrases)){
            $score += 5;
        }


        /*
        * =========================================================
        * 5. POLA "BERAPA + LAYANAN"
        * =========================================================
        */
        if(
            hasKeyword($text, array('BERAPA')) &&
            hasKeyword($text, $service)
        ){
            $score += 4;
        }


        /*
        * =========================================================
        * 6. POLA "UANG + BEROBAT"
        * =========================================================
        */
        if(
            hasKeyword($text, array('UANG')) &&
            hasKeyword($text, array(
                'BEROBAT',
                'PERIKSA',
                'PEMERIKSAAN',
                'DOKTER',
                'PASIEN'
            ))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 7. POLA "BEROBAT + BERAPA"
        * =========================================================
        */
        if(
            hasKeyword($text, array('BEROBAT')) &&
            hasKeyword($text, array('BERAPA'))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 8. POLA "PERIKSA + BERAPA"
        * =========================================================
        */
        if(
            hasKeyword($text, array(
                'PERIKSA',
                'PEMERIKSAAN'
            )) &&
            hasKeyword($text, array('BERAPA'))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 9. PASIEN UMUM
        * =========================================================
        */
        if(
            hasKeyword($text, array('PASIEN UMUM'))
        ){
            $score += 5;
        }


        /*
        * =========================================================
        * 10. TANPA / BUKAN / TIDAK PAKAI BPJS
        * =========================================================
        *
        * Contoh:
        * "bukan BPJS, biaya berobat berapa?"
        * "tanpa BPJS bayarnya berapa?"
        * "nggak pakai BPJS kena berapa?"
        *
        * BPJS di sini adalah konteks pembayaran,
        * bukan intent BPJS utama.
        */
        $nonBpjs = array(
            'TANPA BPJS',
            'BUKAN BPJS',
            'TIDAK PAKAI BPJS',
            'NGGAK PAKAI BPJS',
            'NGGA PAKAI BPJS',
            'GAK PAKAI BPJS',
            'GA PAKAI BPJS',
            'TIDAK MENGGUNAKAN BPJS',
            'NGGAK MENGGUNAKAN BPJS',
            'TANPA KARTU BPJS'
        );

        if(hasPhrase($text, $nonBpjs)){

            if(
                hasKeyword($text, array(
                    'BERAPA',
                    'BIAYA',
                    'HARGA',
                    'TARIF',
                    'BAYAR',
                    'KENA',
                    'UANG'
                ))
            ){
                $score += 6;
            }
        }


        /*
        * =========================================================
        * 11. POLA PEMBAYARAN
        * =========================================================
        */
        $payment = array(
            'HARUS BAYAR',
            'BISA BAYAR',
            'CARA BAYAR',
            'PEMBAYARANNYA',
            'PEMBAYARAN',
            'BAYARNYA',
            'BAYAR',
            'KENA',
            'DIBAYAR'
        );

        if(hasPhrase($text, $payment)){
            $score += 3;
        }


        /*
        * =========================================================
        * 12. DEBUG SCORE
        * =========================================================
        *
        * Jika ingin melihat score saat testing,
        * sementara bisa mengembalikan score.
        *
        * Untuk production tetap boolean.
        */
        return ($score >= 5);
    }

?>