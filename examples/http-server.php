<?php
$port = 1337;

define("ROOT_DIR", dirname(__DIR__));

require_once ROOT_DIR . "/vendor/autoload.php";

/** @var \Symfony\Component\HttpKernel\Kernel $app */

$server = new \Mkusher\SymfonyServerComponent\Http\Server();
$server->on('request', function(\Mkusher\SymfonyServerComponent\Http\Request $request, \Mkusher\SymfonyServerComponent\Server\ResponseInterface $response) use ($app){
    $response->setResponse($app->handle($request));
    $app->terminate($request, $response->getResponse());
});

$server->run(['port' => $port]);

echo "Server is running on http://0.0.0.0:{$port}/\n";

$server->loop();