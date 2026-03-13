<?php
    class Modelshift extends CI_Model{
        
        function periode(){
            $query =
                    "
                        SELECT 
                            TO_CHAR(dt,'FMMonth YYYY','NLS_DATE_LANGUAGE=INDONESIAN') AS PERIODE,
                            TO_CHAR(dt, 'MM.YYYY') AS PERIODE_KEY
                        FROM (
                            SELECT ADD_MONTHS(DATE '2015-01-01', LEVEL-1) dt
                            FROM DUAL
                            CONNECT BY ADD_MONTHS(DATE '2015-01-01', LEVEL-1) <= TRUNC(SYSDATE, 'MM')
                        )
                        ORDER BY dt DESC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        public function perhitunganshift($periode){

			// explode periode (format: 02.2026)
			$exp     = explode('.', $periode);
			$bulan   = $exp[0];
			$tahun   = $exp[1];

			$query = "
				SELECT 
					A.TANGGAL,
					A.BULAN,
					A.TAHUN,
					A.PERIODE,
					TO_CHAR(A.PERIODE,'DD.MM.YYYY') AS PERIODETGL,
					A.NAMAHARI,
					A.NIK,
					A.FLAG,
					TO_CHAR(A.MASUK_LONG, 'HH24:MI') AS JAMMASUK,
					TO_CHAR(A.PULANG_LONG, 'HH24:MI') AS JAMPULANG,
					A.REAL_MASUK AS REALMASUK,
					A.REAL_PULANG AS REALPULANG,
					B.JENIS_PEG,
					B.NAMA AS NAMAKARYAWAN,
					DECODE(B.JENIS_PEG, 'JPM', 'MEDIS', 'JPP', 'PARAMEDIS', 'JPN', 'NON MEDIS', '') AS JENIS_PEGAWAI,
					C.NAMA_JABATAN AS UNIT,
					(SELECT D2.NAMA_JABATAN 
					FROM SR01_STRUKTUR_RS D2 
					WHERE D2.JABATAN_ID = A.SUB_UNIT_ID) AS SUB_UNIT,
					D.JENIS AS JENISLIBUR,
					D.KETERANGAN HARILIBUR,

					CASE
						WHEN A.REAL_MASUK IS NULL OR A.REAL_PULANG IS NULL THEN 0
						ELSE
							CASE
								WHEN D.JENIS='1' THEN
									CASE B.JENIS_PEG
										WHEN 'JPM' THEN E.MEDIS_RAYA
										WHEN 'JPP' THEN E.PARAMEDIS_RAYA
										ELSE E.NONMEDIS_RAYA
									END
								WHEN A.NAMAHARI='MINGGU' THEN
									CASE B.JENIS_PEG
										WHEN 'JPM' THEN E.MEDIS_LIBUR
										WHEN 'JPP' THEN E.PARAMEDIS_LIBUR
										ELSE E.NONMEDIS_LIBUR
									END
								ELSE
									CASE B.JENIS_PEG
										WHEN 'JPM' THEN E.MEDIS_KERJA
										WHEN 'JPP' THEN E.PARAMEDIS_KERJA
										ELSE E.NONMEDIS_KERJA
									END
							END
					END AS NOMINALUANGSHIFT

				FROM HRD_ABSEN_PEGAWAI_PERIODE A

				LEFT JOIN HRD_KARYAWAN_MS B 
					ON B.NIK = A.NIK

				LEFT JOIN SR01_STRUKTUR_RS C 
					ON C.JABATAN_ID = A.UNIT_ID

				LEFT JOIN HRD_CUTI_LIBUR D 
					ON D.TANGGAL = A.PERIODE 
					AND D.BOLEH_REGIST='T'

				-- JOIN langsung + filter INSENTIF disini (bukan subquery!)
				INNER JOIN HRD_ABSEN_SHIFT_MS E 
					ON E.JADWAL_ID = A.JADWAL_ID
					AND E.INSENTIF = 'Y'

				WHERE A.AKTIF = '1'
				AND A.BULAN = ?
				AND A.TAHUN = ?

				ORDER BY A.TANGGAL, C.NAMA_JABATAN, B.NAMA
			";

			$recordset = $this->db->query($query, [$bulan, $tahun]);
			return $recordset->result();
		}

    }
?>