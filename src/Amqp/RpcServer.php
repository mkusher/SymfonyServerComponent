<?php

namespace Mkusher\SymfonyServerComponent\Amqp;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Mkusher\SymfonyServerComponent\Server\AbstractServer;

class RpcServer extends AbstractServer {
    public function run($config = []){
        $this->createConnection($config);
        $this->queue = '';
        $this->tag = '';
        $this->routingKey = $config['routing_key'];
        $this->initExchange($config['exchange']);
        $this->initQueue();

        $this->getChannel()
            ->basic_consume($this->getQueue(), $this->tag, false, false, false, false, [$this, 'onMessage']);
    }

    public function loop(){
        while(count($this->getChannel()->callbacks)){
            $this->getChannel()->wait();
        }
    }

    public function onMessage(AMQPMessage $msg){
        $message = Message::createFromAMQP($msg);
        $request = Request::createFromAmqpMessage($message);
        $response = new Response($this->getChannel(), $this->getExchange(), $message->id);
        $this->emit('message', [$request, $response]);
        $response->send();
    }

    /**
     * @return AMQPConnection
     */
    public function getConnection(){
        return $this->connection;
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel(){
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getExchange(){
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getQueue(){
        return $this->queue;
    }

    protected function createConnection($config){
        $this->connection = new AMQPConnection($config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']);
        $this->channel = $this->getConnection()->channel();
    }
    protected function initExchange($exchange){
        $this->exchange = $exchange;
        $this->getChannel()->exchange_declare($exchange, 'direct', false, false, false);
    }
    protected function initQueue(){

        list($this->queue, ,) = $this->getChannel()->queue_declare($this->queue, false, false, false, false);
        $this->getChannel()->queue_bind($this->queue, $this->exchange, $this->routingKey);
    }

    public function __destruct(){
        $this->getChannel()->close();
        $this->getConnection()->close();
    }

    protected $routingKey;
    protected $tag;
    protected $queue;
    protected $exchange;
    private $channel;
    private $connection;
} 
