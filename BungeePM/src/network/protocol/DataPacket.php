<?php

namespace BungeePM\network\protocol;

class DataPacket
{

    /**
     * @var int
     */
    private $type = 0x001;

    /**
     * @var array $data - Packet must be constructed with encoded data
     */
    private $data = ['serverId' => 0, 'packetId' => 0x001];

    /**
     * @var bool $isEncoded
     */
    private $isEncoded = true;

    /**
     * DataPacket constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData() : array{
        return $this->data;
    }

    /**
     * @param int $packetId
     */
    public function setType(int $packetId){
        $this->type = $packetId;
    }

    /**
     * @return int
     */
    public function getType() : int{
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isEncoded() : bool{
        return $this->isEncoded;
    }

    public function decode(){
        $this->isEncoded = false;
        return json_decode(serialize($this->getData()));
    }

    public function encode(){
        $this->isEncoded = true;
        return json_encode($this->data);
    }
}