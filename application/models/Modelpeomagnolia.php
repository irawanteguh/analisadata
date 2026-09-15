<?php
    class Modelpeomagnolia extends CI_Model{

        function datawebhook(){
            $query =
                    "
                        SELECT A.SESSION_ID, IDEMPOTENCY_KEY, CHAT_ID, MESSAGE_BODY
                        FROM SR01_WHATSAPP_WEBHOOK A
                        WHERE A.LOKASI_ID='001'
                        AND   A.AKTIF='1'
                        AND   A.RESPONSE_STATUS='N'
                        AND   A.PUSH_NAME='Teguh Irawan'
                        -- AND   A.PUSH_NAME IN ('Teguh Irawan','Desty Wijayanti','Fauziyyah','Wahyuni Ari Safitri Rahma','marketingrsudpm','Tisha')
                        ORDER BY A.CREATED_DATE DESC
                        FETCH FIRST 1 ROWS ONLY
                    ";

            $recordset = $this->db->query($query);
            $recordset = $recordset->result();
            return $recordset;
        }

    }
?>