<?php
    class Modelrekaptindakan extends CI_Model{
        
        function datajumlahpasien($dokterid,$startdate,$enddate){
            $query = "
                        SELECT X.*
						FROM(
							SELECT A.JENIS_EPISODE, TGL_MASUK, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK, COUNT(*)JML
							FROM SR01_KEU_EPISODE A
							WHERE A.LOKASI_ID = '001'
							AND A.AKTIF = '1'
							AND A.JENIS_EPISODE = 'O'
							AND A.STATUS_EPISODE <> '99'
							AND A.DOKTER_ID='".$dokterid."'
							AND A.TGL_MASUK >= TO_DATE('".$startdate."','YYYY-MM-DD')
							AND A.TGL_MASUK < TO_DATE('".$enddate."','YYYY-MM-DD') + 1
							AND (
									(
										A.POLI_ID NOT IN (
											'UGD01',
											'APS R0000000001',
											'POLIFISIO',
											'POLIFISOKUP',
											'POLIFISWICARA',
											'HEMOD0000000000'
										)
										AND EXISTS (
											SELECT 1
											FROM SR01_MED_PRWT_TR T
											WHERE T.LOKASI_ID   = '001'
											AND T.AKTIF       = '1'
											AND T.DONE_STATUS = '01'
											AND T.STATUS      = '1'
											AND T.PASIEN_ID   = A.PASIEN_ID
											AND T.EPISODE_ID  = A.EPISODE_ID
										)
									)
									OR A.POLI_ID IN (
										'POLIFISIO',
										'POLIFISOKUP',
										'POLIFISWICARA',
										'HEMOD0000000000',
										'CAPD0000000001'
									)
							)
							GROUP BY JENIS_EPISODE, A.TGL_MASUK

							UNION

							SELECT A.JENIS_EPISODE, TGL_MASUK, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY')TGLMASUK, COUNT(*)JML
							FROM SR01_KEU_EPISODE A
							WHERE A.LOKASI_ID = '001'
							AND A.AKTIF = '1'
							AND A.JENIS_EPISODE = 'I'
							AND A.STATUS_EPISODE <> '99'
							AND A.DOKTER_ID='".$dokterid."'
							AND A.TGL_MASUK >= TO_DATE('".$startdate."','YYYY-MM-DD')
							AND A.TGL_MASUK < TO_DATE('".$enddate."','YYYY-MM-DD') + 1
							GROUP BY JENIS_EPISODE, A.TGL_MASUK
						)X
						ORDER BY JENIS_EPISODE ASC, TGL_MASUK ASC
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

		function dataaktifitasdokter($dokterid,$startdate,$enddate){
            $query = "
                        SELECT Y.*
						FROM(
							SELECT X.*,
								(SELECT NAMA_LAYAN1 FROM SR01_KEU_LAYAN_MS WHERE LAYAN_ID=X.LAYAN_ID)NAMAPELAYANAN,
								(SELECT KATEGORI_ID FROM SR01_KEU_LAYAN_MS WHERE LAYAN_ID=X.LAYAN_ID)KATEGORIID,
								(SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE DOKTER_ID=X.DOKTERID)NAMADOKTER
							FROM(
								SELECT 'TINDAKAN RAWAT INAP / JALAN'JENIS, CREATED_BY DOKTERID, LAYAN_ID, SUM(QTY) AS TOTAL_QTY
								FROM SR01_KEU_TRANSCTR_IT A
								WHERE A.LOKASI_ID='001'
								AND   A.AKTIF='1'
								AND   A.CREATED_DATE >= TO_DATE('".$startdate."','YYYY-MM-DD')
								AND   A.CREATED_DATE < TO_DATE('".$enddate."','YYYY-MM-DD') + 1
								AND   A.LAYAN_ID IS NOT NULL 
								AND   A.LAYAN_ID NOT IN ('ADM03','ADM04','ADM01','ADM02','ADM00','ADM05','ADMPC01','XPENDA000000002','XPENDA000000001','XPENDA000000003')
								AND   A.CREATED_BY = '".$dokterid."'
								GROUP BY CREATED_BY, LAYAN_ID

								UNION

								SELECT 'TINDAKAN ANASTESI'JENIS, ANS_DOKTER_ID DOKTERID, LAYAN_ID, SUM(QTY)
								FROM SR01_KEU_TRANSCTR_IT A
								WHERE A.LOKASI_ID='001'
								AND   A.AKTIF='1'
								AND   A.CREATED_DATE >= TO_DATE('".$startdate."','YYYY-MM-DD')
								AND   A.CREATED_DATE < TO_DATE('".$enddate."','YYYY-MM-DD') + 1
								AND   A.LAYAN_ID IS NOT NULL 
								AND   A.LAYAN_ID NOT IN ('ADM03','ADM04','ADM01','ADM02','ADM00','ADM05','ADMPC01','XPENDA000000002','XPENDA000000001','XPENDA000000003')
								AND   A.ANS_DOKTER_ID = '".$dokterid."'
								GROUP BY ANS_DOKTER_ID, LAYAN_ID

								UNION

								SELECT 'TINDAKAN ANAK'JENIS, ANK_DOKTER_ID DOKTERID, LAYAN_ID, SUM(QTY)
								FROM SR01_KEU_TRANSCTR_IT A
								WHERE A.LOKASI_ID='001'
								AND   A.AKTIF='1'
								AND   A.CREATED_DATE >= TO_DATE('".$startdate."','YYYY-MM-DD')
								AND   A.CREATED_DATE < TO_DATE('".$enddate."','YYYY-MM-DD') + 1
								AND   A.LAYAN_ID IS NOT NULL 
								AND   A.LAYAN_ID NOT IN ('ADM03','ADM04','ADM01','ADM02','ADM00','ADM05','ADMPC01','XPENDA000000002','XPENDA000000001','XPENDA000000003')
								AND   A.ANK_DOKTER_ID= '".$dokterid."'
								GROUP BY ANK_DOKTER_ID, LAYAN_ID
							)X
						)Y
						WHERE Y.KATEGORIID NOT IN ('JKL-LAB','JKL-RAD')
						ORDER BY Y.TOTAL_QTY DESC, NAMADOKTER, JENIS, NAMAPELAYANAN
            ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>