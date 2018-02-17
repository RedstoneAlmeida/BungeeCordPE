<?php

namespace BungeePM\network\protocol\mcpe;


class MCPEPacket
{
    /**
     * @var bool $isEncoded - Constructing with encoded data
     */
    public $isEncoded = true;

    /**
     * @var bool $canCompress
     */
    public $canCompress = true;

    public $buffer = null;

    /**
     * @return bool
     */
    public function canCompress() : bool{
        return $this->canCompress;
    }

    public function encode(){

    }

    public function decode(){
        if($this->buffer{0} !== "\x84"){
            return null;
        }
    }

}