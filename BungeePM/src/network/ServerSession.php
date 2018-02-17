<?php

namespace BungeePM\network;


class ServerSession
{
    /**
     * @var int|mixed
     */
    private $serverId = 0;

    /**
     * @var int
     */
    private $playerCount = 0;

    /**
     * @var string|mixed
     */
    private $password;


    /**
     * ServerSession constructor.
     * @param array $serverData
     */
    public function __construct(array $serverData)
    {
        $this->serverId = $serverData['serverId'];
        $this->playerCount = $serverData['playerCount'];
        $this->password = $serverData['password'];
    }

    /**
     * @return null|string
     */
    public function getPassword() : ?string{
        return $this->password;
    }

    /**
     * @return int
     */
    public function getPlayerCount() : int{
        return $this->playerCount;
    }

    /**
     * @return int
     */
    public function getId() : int{
        return $this->serverId;
    }



}