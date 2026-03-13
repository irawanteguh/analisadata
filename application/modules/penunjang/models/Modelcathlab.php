<?php
    class Modelcathlab extends CI_Model{

        function periode(){
            $query =
                    "
                        SELECT (2014 + LEVEL) AS PERIODE
                        FROM DUAL
                        CONNECT BY LEVEL <= EXTRACT(YEAR FROM SYSDATE) - 2014
                        ORDER BY PERIODE DESC

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datatransaksi($periode){
            $query =
                    "
                        SELECT 
                            NAMA_LAYAN1,
                            NVL(JAN,0) AS JAN,
                            NVL(FEB,0) AS FEB,
                            NVL(MAR,0) AS MAR,
                            NVL(APR,0) AS APR,
                            NVL(MEI,0) AS MEI,
                            NVL(JUN,0) AS JUN,
                            NVL(JUL,0) AS JUL,
                            NVL(AGU,0) AS AGU,
                            NVL(SEP,0) AS SEP,
                            NVL(OKT,0) AS OKT,
                            NVL(NOV,0) AS NOV,
                            NVL(DES,0) AS DES
                        FROM (
                            SELECT 
                                B.NAMA_LAYAN1,
                                EXTRACT(MONTH FROM A.CREATED_DATE) AS BULAN,
                                A.QTY
                            FROM SR01_KEU_TRANSCTR_IT A
                            JOIN SR01_KEU_LAYAN_MS B
                                ON B.LAYAN_ID = A.LAYAN_ID
                                AND B.LOKASI_ID = '001'
                                AND B.AKTIF = '1'
                                AND B.KATEGORI_ID = 'E-206'
                            WHERE A.LOKASI_ID = '001'
                            AND A.AKTIF = '1'
                            AND TO_CHAR(A.CREATED_DATE,'YYYY')='".$periode."'
                        )
                        PIVOT (
                            SUM(QTY)
                            FOR BULAN IN (
                                1  AS JAN,
                                2  AS FEB,
                                3  AS MAR,
                                4  AS APR,
                                5  AS MEI,
                                6  AS JUN,
                                7  AS JUL,
                                8  AS AGU,
                                9  AS SEP,
                                10 AS OKT,
                                11 AS NOV,
                                12 AS DES
                            )
                        )
                        ORDER BY NAMA_LAYAN1
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }






    }
?>