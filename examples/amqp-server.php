<?php

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

$config = [

];

$server = new Mkusher\SymfonyServerComponent\Amqp\RpcServer();

$server->on('message', function(Mkusher\SymfonyServerComponent\Amqp\Request $request, Mkusher\SymfonyServerComponent\Server\ResponseInterface $response) use ($app){
    try {
        $answer = $app->handle($request);
    }
    catch(Exception $e){
        $answer = new \Symfony\Component\HttpFoundation\Response();
        $answer->setStatusCode(500);
    }
    $response->setAnswer($answer);
    $response->end();
    $app->terminate($request, $answer);
});

$server->run($config);

echo "Login Server has started listening to queues\n";

$server->loop();