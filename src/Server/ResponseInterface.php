<?php


namespace Mkusher\SymfonyServerComponent\Server;

use Symfony\Component\HttpFoundation\Response AS SymfonyResponse;

interface ResponseInterface {
    public function send();
    public function setResponse(SymfonyResponse $response);

    /**
     * @return SymfonyResponse
     */
    public function getResponse();
} 