<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Webhook extends CI_Controller{

        public function __construct(){
            parent::__construct();
            $this->load->model("Modelwhatsapp","md");
            $this->load->library('Openwa');
        }

        public function index(){
            $raw     = $this->input->raw_input_stream;
            $payload = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->writeLog(['error' => 'Invalid JSON payload','raw'   => $raw]);
                return $this->jsonResponse(['status'  => false,'message' => 'Invalid JSON payload'], 400);
            }

            $event          = isset($payload['event']) ? $payload['event'] : null;
            $timestamp      = isset($payload['timestamp']) ? $payload['timestamp'] : null;
            $sessionId      = isset($payload['sessionId']) ? $payload['sessionId'] : null;
            $idempotencyKey = isset($payload['idempotencyKey']) ? $payload['idempotencyKey'] : null;
            $deliveryId     = isset($payload['deliveryId']) ? $payload['deliveryId'] : null;


            $message   = isset($payload['data']) ? $payload['data'] : [];
            $messageId = isset($message['id']) ? $message['id'] : null;
            $from      = isset($message['from']) ? $message['from'] : null;
            $to        = isset($message['to']) ? $message['to'] : null;
            $chatId    = isset($message['chatId']) ? $message['chatId'] : null;
            $body      = isset($message['body']) ? $message['body'] : null;
            $type      = isset($message['type']) ? $message['type'] : null;
            $msgTime   = isset($message['timestamp']) ? $message['timestamp'] : null;
            $fromMe    = isset($message['fromMe']) ? $message['fromMe'] : false;
            $isGroup   = isset($message['isGroup']) ? $message['isGroup'] : false;
            $kind      = isset($message['kind']) ? $message['kind'] : null;
            $author    = isset($message['author']) ? $message['author'] : null;

            $responseData = [
                'status'  => true,
                'message' => 'Webhook received',
                'event' => [
                    'name'           => $event,
                    'timestamp'      => $timestamp,
                    'sessionId'      => $sessionId,
                    'idempotencyKey' => $idempotencyKey,
                    'deliveryId'     => $deliveryId
                ],
                'data' => [
                    'id'        => $messageId,
                    'from'      => $from,
                    'to'        => $to,
                    'chatId'    => $chatId,
                    'body'      => $body,
                    'type'      => $type,
                    'timestamp' => $msgTime,
                    'fromMe'    => $fromMe,
                    'isGroup'   => $isGroup,
                    'kind'      => $kind,
                    'author'    => $author
                ]
            ];

            $this->writeLog($payload,$responseData);
            return $this->jsonResponse($responseData, 200);
        }

        private function writeLog($payload,$responseData = null){
            $event             = isset($payload['event']) ? $payload['event'] : '';
            $eventTimestamp    = isset($payload['timestamp']) ? $payload['timestamp'] : '';
            $sessionId         = isset($payload['sessionId']) ? $payload['sessionId'] : '';
            $idempotencyKey    = isset($payload['idempotencyKey']) ? $payload['idempotencyKey'] : '';
            $deliveryId        = isset($payload['deliveryId']) ? $payload['deliveryId'] : '';
            $message           = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : [];
            $messageId         = isset($message['id']) ? $message['id'] : '';
            $sender            = isset($message['from']) ? $message['from'] : '';
            $recipient         = isset($message['to']) ? $message['to'] : '';
            $chatId            = isset($message['chatId']) ? $message['chatId'] : '';
            $messageBody       = isset($message['body']) ? $message['body'] : '';
            $messageType       = isset($message['type']) ? $message['type'] : '';
            $messageTimestamp  = isset($message['timestamp']) ? $message['timestamp'] : '';
            $fromMe            = isset($message['fromMe']) ? $message['fromMe'] : '';
            $isGroup           = isset($message['isGroup']) ? $message['isGroup'] : '';
            $messageKind       = isset($message['kind']) ? $message['kind'] : '';
            $isStatusBroadcast = isset($message['isStatusBroadcast']) ? $message['isStatusBroadcast'] : '';
            $isLidSender       = isset($message['isLidSender']) ? $message['isLidSender'] : '';
            $contact           = isset($message['contact']) && is_array($message['contact']) ? $message['contact'] : [];
            $pushName          = isset($contact['pushName']) ? $contact['pushName'] : '';
            $contactName       = isset($contact['name']) ? $contact['name'] : '';
            $rawPayload        = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $data = [
                'EVENT'               => $event,
                'EVENT_TIMESTAMP'     => $eventTimestamp,
                'SESSION_ID'          => $sessionId,
                'IDEMPOTENCY_KEY'     => $idempotencyKey,
                'DELIVERY_ID'         => $deliveryId,
                'MESSAGE_ID'          => $messageId,
                'SENDER'              => $sender,
                'RECIPIENT'           => $recipient,
                'CHAT_ID'             => $chatId,
                'MESSAGE_BODY'        => $messageBody,
                'MESSAGE_TYPE'        => $messageType,
                'MESSAGE_TIMESTAMP'   => $messageTimestamp,
                'FROM_ME'             => $fromMe,
                'IS_GROUP'            => $isGroup,
                'MESSAGE_KIND'        => $messageKind,
                'IS_STATUS_BROADCAST' => $isStatusBroadcast,
                'IS_LID_SENDER'       => $isLidSender,
                'PUSH_NAME'           => $pushName,
                'CONTACT_NAME'        => $contactName,
                'RAW_PAYLOAD'         => $rawPayload
            ];


            if(empty($this->md->checklogwebhook($idempotencyKey))){
                $this->md->insertlogwebhook($data);
            }
        }

        private function jsonResponse($data, $httpCode = 200){
            http_response_code($httpCode);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data,JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES);
            exit;
        }
    }
?>