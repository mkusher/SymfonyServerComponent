<?php

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

$config = [
    'host' => '',
    'user' => '',
    'password' => '',
    'port' => '',
    'vhost' => '',
    'exchange' => '',
    'routing_key' => ''
];

$server = new Mkusher\SymfonyServerComponent\Amqp\RpcServer();

$server->on('message', function(Mkusher\SymfonyServerComponent\Amqp\Request $request, Mkusher\SymfonyServerComponent\Server\ResponseInterface $response) use ($app){
    $response->setResponse($app->handle($request));
    $app->terminate($request, $response->getResponse());
});

$server->run($config);

echo "Server has started listening to queues\n";

$server->loop();