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
     * @var bool $debugMode
     */
    public static $debugMode = true;

    /**
     * BungeePE constructor.
     * @param string $bindIp
     * @param int $bindPort
     * @param $password
     * @param $tickTimeout
     */
    public function __construct(string $bindIp, int $bindPort, $password, $tickTimeout)
    {
       if(PHP_VERSION_ID < 70200){
           Logger::error("You must use PHP 7.2 or higher");
           exit(127);
       }
       if(PHP_SHLIB_SUFFIX !== "dll"){
           Logger::error("Use dll extensions and BungeeCord Custom build! Your build has invalid SHLIB_SUFFIX.");
           exit(127);
       }
       if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'){
             Logger::error("BungeeCord support only Linux,CentOS & MacOS! FOR WINDOWS THERE IS MISSING SOCKET_UTILS EXTENSION");
             exit(127);
       }
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