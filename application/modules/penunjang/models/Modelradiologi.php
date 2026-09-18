<?php
    class Modelradiologi extends CI_Model{

        function datapemeriksaan($startDate, $endDate){
            $query = "
                SELECT 
                    A.TANGGAL_ORDER,
                    TANGGAL_APPROVE,
                    NO_RM,
                    NAMA_PASIEN,
                    NO_REGISTER,
                    NO_RONTGEN,
                    ID_DETAIL_RADIOLOGI,
                    KODE_PEMERIKSAAN,
                    NAMA_PEMERIKSAAN,
                    KODE_RUANGAN_PENGIRIM,
                    RUANGAN_PENGIRIM,
                    CASE
                        WHEN KODE_RUANGAN_PENGIRIM = 'APS' THEN 'APS'
                        WHEN KODE_RUANGAN_PENGIRIM = 'UGD01' THEN 'IGD'
                        WHEN KODE_RUANGAN_PENGIRIM LIKE 'P%' THEN 'RAWAT JALAN'
                        ELSE 'RAWAT INAP'
                    END TIPE,
                    (
                        SELECT NAMA_GROUP
                        FROM SR01_KEU_LAYAN_MS_GROUP
                        WHERE AKTIF = '1'
                            AND JENIS = 'RAD'
                            AND GROUP_ID = (
                                SELECT PARENT_ID
                                FROM SR01_KEU_LAYAN_MS_GROUP
                                WHERE AKTIF = '1'
                                    AND JENIS = 'RAD'
                                    AND LAYAN_ID = A.KODE_PEMERIKSAAN
                            )
                    ) NAMAGROUP
                FROM RAD_MANAGER.RIS_IN A
                WHERE A.TANGGAL_APPROVE >= TO_DATE(
                    '".$startDate."',
                    'YYYY-MM-DD\"T\"HH24:MI:SS'
                )
                AND A.TANGGAL_APPROVE <= TO_DATE(
                    '".$endDate."',
                    'YYYY-MM-DD\"T\"HH24:MI:SS'
                )
                ORDER BY TIPE ASC
            ";

            $recordset = $this->db->query($query);
            return $recordset->result();
        }
        
    }
?>