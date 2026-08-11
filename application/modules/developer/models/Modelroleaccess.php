<?php
    class Modelroleaccess extends CI_Model{
        
        function datauser(){
            $query =
                    "
                        SELECT A.USER_ID, NAMA
                        FROM SR01_GEN_USER_DATA A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        ORDER BY NAMA ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function datamodules($userid){
            $query =
                    "
                        SELECT A.MODULES_ID, MODULES_HEADER_ID, MODULES_NAME, PARENT, ICON, PACKAGE, DEF_CONTROLLER,
                            (SELECT TRANS_ID FROM SR01_GEN_ROLE_ACCESS_DT WHERE LOKASI_ID='001' AND AKTIF='1' AND MODULES_ID=A.MODULES_ID AND USER_ID='".$userid."')TRANS_ID
                        FROM SR01_GEN_MODULES_MS A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.SOURCECODE='ANALISA'
                        ORDER BY URUT ASC
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function checkdata($userid,$switchId){
            $query =
                    "
                        SELECT A.TRANS_ID
                        FROM SR01_GEN_ROLE_ACCESS_DT A
                        WHERE A.USER_ID='".$userid."'
                        AND   A.MODULES_ID='".$switchId."'
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

        function updaterole($userid,$modulesid,$data){           
            $sql =   $this->db->update("SR01_GEN_ROLE_ACCESS_DT",$data,array("USER_ID"=>$userid,"MODULES_ID"=>$modulesid));
            return $sql;
        }

        function insertrole($data){           
            $sql =   $this->db->insert("SR01_GEN_ROLE_ACCESS_DT",$data);
            return $sql;
        }


    }
?>