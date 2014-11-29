<?php

namespace Mkusher\SymfonyServerComponent\Server;

abstract class AbstractServer {
    /**
     * @param string $eventName
     * @param callable $callback
     * @return ServerInterface
     */
    public function on($eventName, $callback){
        if(!array_key_exists($eventName, $this->events)){
            $this->events[$eventName] = [];
        }
        $this->events[$eventName][] = $callback;
        return $this;
    }

    /**
     * @param string $eventName
     * @param array $params
     * @return ServerInterface
     */
    public function emit($eventName, array $params){
        if(!array_key_exists($eventName, $this->events))
            return $this;
        foreach($this->events[$eventName] AS $callback){
            call_user_func_array($callback, $params);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function clear(){
        $this->events = [];
        return $this;
    }

    private $events = [];
} 