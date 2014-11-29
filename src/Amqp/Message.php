<?php

namespace Mkusher\SymfonyServerComponent\Amqp;

use PhpAmqpLib\Message\AMQPMessage;

class Message {
    public $id;
    public $method;
    public $params;
    public static function createFromAMQP(AMQPMessage $message){
        $body = json_decode($message->body, true);
        $msg = new static;
        $msg->id = $body['id'];
        $msg->method = $body['method'];
        $msg->params = $body['params'];
        return $msg;
    }
} 