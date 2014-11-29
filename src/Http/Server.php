<?php


namespace Mkusher\SymfonyServerComponent\Http;

use Mkusher\SymfonyServerComponent\Server\AbstractServer;
use React\EventLoop\Factory;
use React\Socket\Server AS ReactSocketServer;
use React\Http\Server AS ReactHttpServer;
use React\Http\Request AS ReactRequest;
use React\Http\Response AS ReactResponse;

class Server extends AbstractServer {
    public function __construct(){
        $this->loop = Factory::create();
        $this->server = new ReactSocketServer($this->loop);
    }
    public function run($config = []){
        $http = new ReactHttpServer($this->server);
        $server = $this;
        $http->on('request', function (ReactRequest $request, ReactResponse $response) use ($server){
            $request->once('data', function($data) use($request, $response, $server){
                $server->onRequest($request, $response, $data);
            });
        });
        $this->server->listen($config['port']);
    }
    public function loop(){
        $this->loop->run();
    }
    public function onRequest($reactRequest, $reactResponse, $data){
        $request = Request::createFromReact($reactRequest, $data);
        $response = new Response($reactResponse);
        $this->emit('request', [$request, $response]);
        $this->sendResponse($response);
    }

    public function sendResponse(Response $response){
        $response->send();
    }

    private $config;
    /** @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop */
    private $loop;
    /** @var ReactSocketServer */
    private $server;
} 