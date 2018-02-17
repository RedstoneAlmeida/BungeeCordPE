<?php

namespace BungeePM;

use BungeePM\network\BungeeUDPSocket;
use BungeePM\utils\Logger;

class BungeePE{

    /**
     * @var BungeeUDPSocket $socket
     */
    private $socket;

    /**
     * BungeePE constructor.
     * @param string $bindIp
     * @param int $bindPort
     * @param $password
     * @param $tickTimeout
     */
    public function __construct(string $bindIp, int $bindPort, $password, $tickTimeout)
    {
       $this->socket = new BungeeUDPSocket($bindIp, $bindPort, $password);
    }

    public function closeAllServers() : bool{
        foreach($this->socket->getServers() as $server){
            //TODO: Implement close function
            return true;
        }
        return false;
    }
}