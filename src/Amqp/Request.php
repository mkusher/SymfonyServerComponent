<?php


namespace Mkusher\SymfonyServerComponent\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request AS BaseRequest;

class Request extends BaseRequest{
    /**
     * @param Message $message
     * @return static
     */
    public static function createFromAmqpMessage(Message $message){

        $server = array_merge($_SERVER, [
            'REQUEST_URI' => '/' . $message->method,
            'QUERY_STRING' => '',
            'REQUEST_METHOD' => 'GET'
        ]);

        $request = new static([], $message->params, [], [], [], $server);

        return $request;
    }
} 