<?php


namespace App\Helpers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPAbstractCollection;
use PhpAmqpLib\Wire\AMQPTable;

class MailHelper
{
    public static function send($payload, $routing_key = '') {
        $exchange = "EmailQueue";

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $channel = $connection->channel();
        $message = new AMQPMessage(json_encode($payload));
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
