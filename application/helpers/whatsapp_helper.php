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

    function isGreeting($message,$ignoreCase = true,$ignorePunctuation = true){

        $originalMessage = trim($message);

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $greeting = array(
            // Halo
            'HALO',
            'HALO!',
            'HALLO',
            'HELLO',
            'HAI',
            'HI',
            'HEI',
            'HEY',
            'HAY',
            'HII',
            'HIII',
            'HIII',
            'HY',

            // Sapaan waktu
            'PAGI',
            'SELAMAT PAGI',
            'SIANG',
            'SELAMAT SIANG',
            'SORE',
            'SELAMAT SORE',
            'MALAM',
            'SELAMAT MALAM',

            // Salam Islam
            'ASSALAMUALAIKUM',
            'ASSALAMUALAIKUM WR WB',
            'ASSALAMUALAIKUM WR. WB.',
            'ASSALAMUALAIKUM WARAHMATULLAHI WABARAKATUH',
            'ASSALAMUALAIKUM WARAHMATULLAH WABARAKATUH',
            'ASS WR WB',

            // Sapaan umum
            'PERMISI',
            'MISI',
            'HALO ADMIN',
            'HAI ADMIN',
            'HI ADMIN',
            'HALO MIN',
            'HAI MIN',
            'HI MIN',
            'HALO RSUD',
            'HALO RSUD PASAR MINGGU',
            'HALO PEO',

            // Sapaan awal percakapan
            'SAYA MAU BERTANYA',
            'MAU TANYA',
            'MAU BERTANYA',
            'MINTA INFORMASI',
            'BUTUH INFORMASI'
        );

        foreach($greeting as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', '', $compare);
                $compare = trim($compare);
            }

            if($originalMessage === $compare){
                return true;
            }
        }

        return false;
    }

    function isBiaya($message,$ignoreCase = true,$ignorePunctuation = true){

        $originalMessage = trim($message);

        if($originalMessage == ''){
            return false;
        }

        // Normalisasi huruf
        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        // Normalisasi tanda baca
        if($ignorePunctuation){
            $originalMessage = preg_replace('/[^\p{L}\p{N}\s]/u',' ',$originalMessage);
        }

        // Normalisasi spasi
        $originalMessage = preg_replace('/\s+/u',' ',trim($originalMessage));

        /*
        * 1. Kata utama biaya
        */
        $keywordBiaya = array(
            'BIAYA',
            'HARGA',
            'TARIF',
            'ONGKOS',
            'BAYAR',
            'PEMBAYARAN',
            'HARGANYA',
            'BIAYANYA',
            'TARIFNYA'
        );

        /*
        * 2. Pola pertanyaan biaya
        */
        $patternBiaya = array(
            'BERAPA BIAYA',
            'BERAPA HARGA',
            'BERAPA TARIF',
            'BIAYA BERAPA',
            'HARGA BERAPA',
            'TARIF BERAPA',
            'BIAYANYA BERAPA',
            'HARGANYA BERAPA',
            'TARIFNYA BERAPA',
            'BERAPA YANG HARUS DIBAYAR',
            'HARUS BAYAR BERAPA',
            'BAYAR BERAPA',
            'KENA BERAPA',
            'TOTAL BAYAR BERAPA',
            'BIAYA NYA BERAPA',
            'HARGA NYA BERAPA',
            'TARIF NYA BERAPA'
        );

        /*
        * 3. Konteks pelayanan
        */
        $keywordLayanan = array(
            'PENDAFTARAN',
            'DAFTAR',
            'REGISTRASI',
            'PEMERIKSAAN',
            'DOKTER',
            'KONSULTASI',
            'POLI',
            'TINDAKAN',
            'TREATMENT',
            'RAWAT JALAN',
            'RAWAT INAP',
            'IGD',
            'UGD',
            'LABORATORIUM',
            'LAB',
            'RADIOLOGI',
            'USG',
            'RONTGEN',
            'KULIT'
        );

        /*
        * 4. Jika ada pola pertanyaan biaya
        */
        foreach($patternBiaya as $pattern){

            if(strpos($originalMessage,$pattern) !== false){
                return true;
            }
        }

        /*
        * 5. Jika ada keyword biaya langsung
        */
        foreach($keywordBiaya as $keyword){

            $pattern = '/\b'.preg_quote($keyword,'/').'\b/u';

            if(preg_match($pattern,$originalMessage)){
                return true;
            }
        }

        /*
        * 6. Kombinasi "berapa" + konteks layanan
        *
        * Contoh:
        * PENDAFTARAN BERAPA
        * DOKTER BERAPA
        * POLI KULIT BERAPA
        */
        if(strpos($originalMessage,'BERAPA') !== false){

            foreach($keywordLayanan as $layanan){

                $pattern = '/\b'.preg_quote($layanan,'/').'\b/u';

                if(preg_match($pattern,$originalMessage)){

                    return true;
                }
            }
        }

        return false;
    }

    function isPendaftaran($message,$ignoreCase = true,$ignorePunctuation = true){

        $originalMessage = trim($message);

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
            $originalMessage = preg_replace('/\s+/', ' ', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $pendaftaran = array(

            // Pendaftaran
            'DAFTAR',
            'PENDAFTARAN',
            'MENDAFTAR',
            'REGISTRASI',
            'REGISTRASI PASIEN',
            'DAFTAR PASIEN',

            // Daftar online
            'DAFTAR ONLINE',
            'PENDAFTARAN ONLINE',
            'DAFTAR LEWAT ONLINE',
            'DAFTAR MELALUI ONLINE',

            // WhatsApp
            'DAFTAR WA',
            'DAFTAR VIA WA',
            'DAFTAR LEWAT WA',
            'DAFTAR MELALUI WA',
            'PENDAFTARAN WA',

            // Daftar langsung
            'DAFTAR LANGSUNG',
            'DAFTAR KE RS',
            'DAFTAR DI RS',
            'DAFTAR RUMAH SAKIT',
            'PENDAFTARAN LANGSUNG'
        );

        foreach($pendaftaran as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', '', $compare);
                $compare = preg_replace('/\s+/', ' ', $compare);
                $compare = trim($compare);
            }

            if(strpos($originalMessage, $compare) !== false){
                return true;
            }
        }

        return false;
    }

    function isBPJS($message,$ignoreCase = true,$ignorePunctuation = true){

        $originalMessage = trim($message);

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
            $originalMessage = preg_replace('/\s+/', ' ', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $bpjs = array(

            // BPJS
            'BPJS',
            'BPJS KESEHATAN',
            'PASIEN BPJS',
            'PESERTA BPJS',

            // Rujukan
            'RUJUKAN',
            'SURAT RUJUKAN',
            'RUJUKAN BPJS',
            'SURAT RUJUKAN BPJS',

            // Faskes
            'FASKES',
            'FASKES 1',
            'FASKES SATU',
            'FKTP',

            // Kombinasi pertanyaan
            'BPJS RUJUKAN',
            'BPJS FASKES',
            'BPJS FASKES 1'
        );

        foreach($bpjs as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', '', $compare);
                $compare = preg_replace('/\s+/', ' ', $compare);
                $compare = trim($compare);
            }

            if(strpos($originalMessage, $compare) !== false){
                return true;
            }
        }

        return false;
    }

    function isAlamat($message, $ignoreCase = true, $ignorePunctuation = true){

        $originalMessage = trim($message);

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
        }

        // Normalisasi spasi
        $originalMessage = preg_replace('/\s+/', ' ', $originalMessage);
        $originalMessage = trim($originalMessage);

        $alamat = array(
            'ALAMAT',
            'ALAMAT RS',
            'ALAMAT RSUD',
            'ALAMAT RUMAH SAKIT',
            'ALAMAT RSUD PASAR MINGGU',
            'LOKASI',
            'LOKASI RS',
            'LOKASI RSUD',
            'LOKASI RUMAH SAKIT',
            'LOKASI RSUD PASAR MINGGU',
            'RSUD PASAR MINGGU DIMANA',
            'RSUD PASAR MINGGU DI MANA',
            'RSUD PASAR MINGGU ADA DIMANA',
            'RSUD PASAR MINGGU ADA DI MANA',
            'RSUD PASAR MINGGU LOKASINYA DIMANA',
            'RSUD PASAR MINGGU LOKASINYA DI MANA',
            'DIMANA RSUD PASAR MINGGU',
            'DI MANA RSUD PASAR MINGGU',
            'DIMANA ALAMAT RSUD PASAR MINGGU',
            'DI MANA ALAMAT RSUD PASAR MINGGU'
        );

        foreach($alamat as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', '', $compare);
            }

            // Normalisasi spasi keyword
            $compare = preg_replace('/\s+/', ' ', $compare);
            $compare = trim($compare);

            if($originalMessage === $compare){
                return true;
            }
        }

        return false;
    }

    function isKeluhan($message, $ignoreCase = true, $ignorePunctuation = true){

        $originalMessage = trim($message);

        if(empty($originalMessage)){
            return false;
        }

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', ' ', $originalMessage);
            $originalMessage = preg_replace('/\s+/', ' ', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $keluhan = array(
            'KELUHAN',
            'SAYA MAU MENGAJUKAN KELUHAN',
            'MAU MENGAJUKAN KELUHAN',
            'INGIN MENGAJUKAN KELUHAN',
            'SAYA MAU KOMPLAIN',
            'MAU KOMPLAIN',
            'INGIN KOMPLAIN',
            'KOMPLAIN',
            'COMPLAIN',
            'PENGADUAN',
            'ADUAN',
            'SAYA MAU MENGADU',
            'MAU MENGADU',
            'INGIN MENGADU',
            'ADA MASALAH',
            'ADA KENDALA',
            'SAYA PUNYA KELUHAN',
            'SAYA INGIN MENYAMPAIKAN KELUHAN',
            'SAYA MAU MENYAMPAIKAN KELUHAN'
        );

        foreach($keluhan as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', ' ', $compare);
                $compare = preg_replace('/\s+/', ' ', $compare);
                $compare = trim($compare);
            }

            // Exact match
            if($originalMessage === $compare){
                return true;
            }

            // Match jika pesan mengandung keyword/kalimat keluhan
            if(strpos($originalMessage, $compare) !== false){
                return true;
            }
        }

        return false;
    }

    function isJamBesuk($message, $ignoreCase = true, $ignorePunctuation = true){

        $originalMessage = trim($message);

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $jamBesuk = array(
            'JAM BESUK',
            'JAM BEZUK',
            'JADWAL BESUK',
            'WAKTU BESUK',
            'WAKTU BEZUK',
            'JADWAL KUNJUNGAN',
            'JAM KUNJUNGAN',
            'JAM VISIT',
            'JAM JENGUK',
            'WAKTU JENGUK',
            'JADWAL JENGUK',
            'BESUK PASIEN',
            'MAU BESUK',
            'MAU MENJENGUK',
            'BOLEH BESUK',
            'KAPAN BOLEH BESUK',
            'KAPAN JAM BESUK',
            'JAM BESUK PASIEN',
            'JADWAL BESUK PASIEN'
        );

        foreach($jamBesuk as $item){

            $compare = $item;

            if($ignoreCase){
                $compare = strtoupper($compare);
            }

            if($ignorePunctuation){
                $compare = preg_replace('/[[:punct:]]/', '', $compare);
                $compare = trim($compare);
            }

            if(strpos($originalMessage, $compare) !== false){
                return true;
            }
        }

        return false;
    }
?>