<?php
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

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
            $originalMessage = trim($originalMessage);
        }

        $biaya = array(

            // Biaya umum
            'BIAYA',
            'HARGA',
            'TARIF',
            'BERAPA BIAYANYA',
            'BERAPA HARGANYA',
            'BERAPA TARIFNYA',

            // Pemeriksaan
            'BIAYA PEMERIKSAAN',
            'HARGA PEMERIKSAAN',
            'TARIF PEMERIKSAAN',

            // Dokter
            'BIAYA DOKTER',
            'HARGA DOKTER',
            'TARIF DOKTER',
            'BIAYA KONSULTASI',
            'HARGA KONSULTASI',
            'TARIF KONSULTASI',

            // Treatment
            'BIAYA TREATMENT',
            'HARGA TREATMENT',
            'TARIF TREATMENT',

            // Poli kulit
            'BIAYA POLI KULIT',
            'HARGA POLI KULIT',
            'TARIF POLI KULIT',
            'BIAYA DOKTER KULIT',
            'HARGA DOKTER KULIT',
            'TARIF DOKTER KULIT'
        );

        foreach($biaya as $item){

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

        if($ignoreCase){
            $originalMessage = strtoupper($originalMessage);
        }

        if($ignorePunctuation){
            $originalMessage = preg_replace('/[[:punct:]]/', '', $originalMessage);
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
            'SAYA INGIN MENYAMPAIKAN KELUHAN'
        );

        foreach($keluhan as $item){

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

            if($originalMessage === $compare){
                return true;
            }
        }

        return false;
    }
?>