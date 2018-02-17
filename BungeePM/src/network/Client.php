<?php

namespace BungeePM\network;


class Client
{

    /**
     * @var string $ip
     */
    public $ip;

    /**
     * @var int $port
     */
    public $port;

    /**
     * @var string $username
     */
    public $username;

    /**
     * Client constructor.
     * @param string $username
     * @param $clientHost
     * @param $clientPort
     */
    public function __construct(string $username, $clientHost, $clientPort)
    {
         $this->ip = $clientHost;
         $this->username = $username;
         $this->port = $clientPort;
    }

}