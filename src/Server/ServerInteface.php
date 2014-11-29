<?php

namespace Mkusher\SymfonyServerComponent\Server;

interface ServerInterface {
    /**
     * @param array $config
     * @return self
     */
    public function run($config = []);

    /**
     * @return void
    */
    public function loop();

    /**
     * @param string $eventName
     * @param callable $callback
     * @return self
     */
    public function on($eventName, $callback);

    /**
     * @param string $eventName
     * @param array $params
     * @return self
     */
    public function emit($eventName, array $params);
} 