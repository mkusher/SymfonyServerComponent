<?php
/**
 * This file is part of the webapp package.
 *
 * (c) Aleh Kashnikau <aleh.kashnikau@gmail.com>
 *
 * Created: 11/09/2014 2:58 PM
 */
namespace Mkusher\SymfonyServerComponent\Http;

use Symfony\Component\HttpFoundation\Request AS BaseRequest;
use React\Http\Request AS ReactRequest;

class Request extends BaseRequest{
    public static function createFromReact(ReactRequest $reactRequest, $data){
        $requestBody = [];
        parse_str(html_entity_decode($data), $requestBody);
        $server = array_merge($_SERVER, [
            'REQUEST_URI' => $reactRequest->getPath(),
            'QUERY_STRING' => '',
            'REQUEST_METHOD' => $reactRequest->getMethod()
        ]);
        $headers = $reactRequest->getHeaders();

        $request = new static($reactRequest->getQuery(), $requestBody, [], self::getCookies($headers), [], $server);

        return $request;
    }

    protected static function getCookies($headers){
        $cookies = [];
        $httpCookies = array_key_exists('Cookie', $headers) ? $headers['Cookie'] : false;
        if(!$httpCookies)
            return $cookies;

        foreach(explode( '; ', $httpCookies) AS $cookieStr){
            list($cookieName, $cookieValue) = explode('=', $cookieStr);
            $cookies[$cookieName] = $cookieValue;
        }
        return $cookies;
    }
}