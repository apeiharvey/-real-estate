<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Crypt;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPAbstractCollection;
use PhpAmqpLib\Wire\AMQPTable;
use Carbon\Carbon;

class BroadcastHelper
{
    /*
        payload = [dynamic json]
        event = AGREGASI_EVENT
        exchange = KLG_ECOMMERCE_TRANSACTION
        routing_key = ""
    */

    public static function send($request, $payload, $routing_key = '', $replace_timestamp = '') {
        $event = "AGREGASI_EVENT";
        $exchange = "klg_ecommerce_transaction";

        $payload['vendor_id'] = !empty($payload['vendor_id']) ? $payload['vendor_id'] :
                                    (!empty(auth()->user()->user_ref_id)?auth()->user()->user_ref_id:0);
        $payload['timestamp'] = ($replace_timestamp?$replace_timestamp:Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z'));
        $payload['session_id'] = !empty($payload['session_id']) ? $payload['session_id'] : $request->cookie("sid");
        $payload['ip'] = $request->getClientIp();
        $payload['browser'] = $request->server('HTTP_USER_AGENT');


        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $channel = $connection->channel();
        $message = new AMQPMessage(json_encode($payload));
        $headers = new AMQPTable(array(
            'event' => $event
        ));
        $message->set('application_headers', $headers);
        $channel->basic_publish($message, $exchange, $routing_key);
        $channel->close();
        try {
            $connection->close();
            return array(
                "result_status" => "SUCCESS",
                "result_message" => "Broadcast successfully sent!"
            );
        } catch (Exception $e) {
            return array(
                "result_status" => "ERROR",
                "result_message" => "An error occurred while broadcasting data!",
                "result_exception" => $e
            );
        }
    }

    public static function send_awb($request, $payload, $routing_key = '') {
        $event = "REQUEST_GENERATE_AWB";
        $exchange = "klg_ecommerce_transaction";

//        $payload['vendor_id'] = !empty($payload['vendor_id']) ? $payload['vendor_id'] :
//            (!empty(auth()->user()->user_ref_id)?auth()->user()->user_ref_id:0);
//        $payload['timestamp'] = date("Y-m-d\TH:i:s\Z");
//        $payload['session_id'] = !empty($payload['session_id']) ? $payload['session_id'] : $request->cookie("sid");
//        $payload['ip'] = $request->getClientIp();
//        $payload['browser'] = $request->server('HTTP_USER_AGENT');

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $channel = $connection->channel();
        $message = new AMQPMessage(json_encode($payload));
        $headers = new AMQPTable(array(
            'event' => $event
        ));
        $message->set('application_headers', $headers);
        $channel->basic_publish($message, $exchange, $routing_key);
        $channel->close();
        try {
            $connection->close();
            return array(
                "result_status" => "SUCCESS",
                "result_message" => "Broadcast successfully sent!"
            );
        } catch (Exception $e) {
            return array(
                "result_status" => "ERROR",
                "result_message" => "An error occurred while broadcasting data!",
                "result_exception" => $e
            );
        }
    }
}
