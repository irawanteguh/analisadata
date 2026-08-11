<?php
    class Modelsign extends CI_Model{

        function login($username,$password){
            $query =
                    "
                        SELECT A.USER_ID
                        FROM SR01_GEN_USER_DATA A
                        WHERE A.AKTIF = '1'
                        AND   UPPER(A.USER_ID)=UPPER('".$username."')
                        AND   UPPER(A.PASSWORD)=UPPER('".$password."')
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

        function datasession($userid){
            $query =
                    "
                        SELECT A.USER_ID, NAMA, DOKTER_ID                            
                        FROM SR01_GEN_USER_DATA A
                        WHERE A.AKTIF = '1'
                        AND   UPPER(A.USER_ID) = UPPER('".$userid."')

                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->row();
            return $recordset;
        }

    }
?>