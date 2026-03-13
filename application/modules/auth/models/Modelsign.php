<?php
    class Modelsign extends CI_Model{

        function login($username,$password){
            $query =
                    "
                        select a.USER_ID
                        from sr01_gen_user_data a
                        where a.aktif='1'
                        and   upper(a.user_id)=upper('".$username."')
                        and   upper(a.password)=upper('".$password."')
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function datasession($userid){
            $query =
                    "
                        SELECT
                            u.USER_ID,
                            u.NAMA,
                            SUBSTR(u.NAMA,1,1) AS INISIAL,
                            'INSTA0000000008'  AS UNITID,
                            t.NILAI            AS TARGET,
                            (t.NILAI * ((t.TARGET_RAD + t.GIZI) / 100)) AS TARGETPPC,
                            ROUND(
                                (t.NILAI * ((t.TARGET_RAD + t.GIZI) / 100)) / 365,
                                0
                            ) AS TARGETHARIAN,
                            (SELECT NAMA_JABATAN FROM SR01_STRUKTUR_RS WHERE JABATAN_ID='INSTA0000000008')NAMAUNIT
                        FROM SR01_GEN_USER_DATA u
                        CROSS JOIN SR01_EBITDA_TARGET_RS t
                        WHERE u.AKTIF = '1'
                        AND   UPPER(u.USER_ID) = UPPER('".$userid."')

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

    }
?>