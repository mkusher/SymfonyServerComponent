<?php

namespace Mkusher\SymfonyServerComponent\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Mkusher\SymfonyServerComponent\Server\ResponseInterface;
use Symfony\Component\HttpFoundation\Response AS HttpResponse;

class Response implements ResponseInterface{
    public function __construct(AMQPChannel $channel, $exchange, $id){
        $this->setChannel($channel);
        $this->exchange = $exchange;
        $this->message = new AMQPMessage();
        $this->routing_key = "";
        $this->id = $id;
    }
    public function getResponse(){
        return $this->answer;
    }
    public function setResponse(HttpResponse $answer){
        $this->answer = $answer;
        $data = [
            'id' => $this->id
        ];
        if($answer->getStatusCode() < 400){
            $data['result'] = $answer->getContent();
        }
        else {
            $data['error'] = [
                'code' => $answer->getStatusCode(),
                'data' => $answer->getContent()
            ];
        }
        $this->routing_key = "answer.".$this->id;
        $this->message->setBody(json_encode($data));
    }
    public function send(){
        echo $this->message->body;
        $this->getChannel()->basic_publish($this->message, $this->exchange, $this->routing_key);
    }

    public function setChannel(AMQPChannel $channel){
        $this->channel = $channel;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel(){
        return $this->channel;
    }

    public function getMessage(){
        return $this->message;
    }

    protected $message;
    protected $routing_key;
    private $answer;
    private $id;
    private $exchange;
    private $channel;
} 