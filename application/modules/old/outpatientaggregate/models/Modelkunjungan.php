<?php
    class Modelkunjungan extends CI_Model{

        function masterdokter(){
            $query =
                    "
                        SELECT ''DOKTER_ID,'--PILIH SEMUA--'NAMA
                        FROM DUAL
                        UNION
                        SELECT A.DOKTER_ID, UPPER(NAMA)NAMA
                        FROM SR01_MED_DOKTER_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.DOKTER_ID NOT IN ('APS','DRRSUD')
                        ORDER BY NAMA ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function masterpoli(){
            $query =
                    "
                        SELECT ''POLI_ID,'--PILIH SEMUA--'KETERANGAN
                        FROM DUAL
                        UNION
                        SELECT A.POLI_ID, KETERANGAN
                        FROM SR01_MED_POLI_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        ORDER BY KETERANGAN ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datakunjungan($startdate,$endate,$dokterid,$poliid){
            $query =
                    "
                        SELECT A.PASIEN_ID, ''''||EPISODE_ID EPISODEID, POLI_ID, DOKTER_ID, REKANAN_ID, TO_CHAR(A.TGL_MASUK,'DD.MM.YYYY HH24:MI:SS')TGLMASUK, TO_CHAR(A.TGL_KELUAR,'DD.MM.YYYY HH24:MI:SS')TGLKELUAR,
                            (SELECT ''''||INT_PASIEN_ID FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID)MRPASIEN,
                            SR01_GET_SUFFIX(A.PASIEN_ID)NAMAPASIEN,
                            (SELECT DECODE(SEX_ID, 'L','LAKI-LAKI','PEREMPUAN') FROM SR01_GEN_PASIEN_MS WHERE LOKASI_ID='001' AND AKTIF='1' AND PASIEN_ID=A.PASIEN_ID)JENISKELAMIN,
                            (SELECT NAMA FROM SR01_KEU_REKANAN_MS WHERE LOKASI_ID='001' AND REKANAN_ID=A.REKANAN_ID)PROVIDER,
                            (SELECT KETERANGAN FROM SR01_MED_POLI_MS WHERE LOKASI_ID='001' AND POLI_ID=A.POLI_ID)POLITUJUAN,
                            CASE
                                WHEN A.POLI_ID='UGD01' THEN
                                SR01_GET_DOKTER_IGD_AKHIR(A.EPISODE_ID)
                                ELSE
                                (SELECT NAMA FROM SR01_MED_DOKTER_MS WHERE LOKASI_ID='001' AND DOKTER_ID=A.DOKTER_ID)
                            END NAMADOKTER,
                            (
                                SELECT LISTAGG('[' || ICD10_ID || '] ' ||DIAGNOSA||'/batasjenis'||JENIS,';') WITHIN GROUP (ORDER BY JENIS, ICD10_ID) AS ICD10_ID
                                FROM SR01_RM_RESUME_ICD10
                                WHERE PASIEN_ID=A.PASIEN_ID
                                AND   EPISODE_ID=A.EPISODE_ID
                                AND   ICD10_ID<>'-'
                                AND   JNS_R='F'
                            )DIAG
                            
                            
                        FROM SR01_KEU_EPISODE A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.PASIEN_ID NOT LIKE 'A%'
                        AND   A.EPISODE_ID NOT LIKE 'A%'
                        AND   A.JENIS_EPISODE='O'
                        AND   A.STATUS_EPISODE='55'
                        -- AND   A.EPISODE_ID='B123122131441'
                        ".$dokterid."
                        ".$poliid."
                        AND   TRUNC(A.TGL_MASUK) BETWEEN TRUNC(TO_DATE('".$startdate."','YYYY-MM-DD')) AND TRUNC(TO_DATE('".$endate."','YYYY-MM-DD'))
                        ORDER BY TGL_MASUK ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }


    }
?>