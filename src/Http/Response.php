<?php
/**
 * This file is part of the webapp package.
 *
 * (c) Aleh Kashnikau <aleh.kashnikau@gmail.com>
 *
 * Created: 11/09/2014 2:58 PM
 */

namespace Mkusher\SymfonyServerComponent\Http;

use React\Http\Response AS BaseResponse;
use Mkusher\SymfonyServerComponent\Server\ResponseInterface;
use Symfony\Component\HttpFoundation\Response AS SymfonyResponse;

class Response implements ResponseInterface{
    public function __construct(\React\Http\Response $transport){
        $this->transport = $transport;
    }
    public function setResponse(SymfonyResponse $answer){
        $this->answer = $answer;
    }
    public function getResponse(){
        return $this->answer;
    }

    public function send(){
        $this->transport->writeHead($this->getResponse()->getStatusCode(), $this->getHeaders());
        $this->transport->write($this->getResponse()->getContent());
        $this->transport->end();
    }

    public function getHeaders(){
        $headers = $this->getResponse()->headers->all();
        $headers['Set-Cookie'] = [];
        foreach($this->getResponse()->headers->getCookies() AS $cookie){
            /** @var Cookie $cookie */
            $headers['Set-Cookie'][] = $cookie->__toString();
        }
        return $headers;
    }

    /**
     * @var BaseResponse
     */
    private $transport;

    /**
     * @var SymfonyResponse
     */
    private $answer;
}