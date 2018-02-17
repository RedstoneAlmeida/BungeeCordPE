<?php

namespace BungeePM\queue;

class MessageQueue{

    /**
     * @var array $messages
     */
    public $messages = [];

    /**
     * @param string $message
     */
    public function addMessage(string $message){
        $this->messages[] = $message;
    }

    public function getNextMessage(){
        //TODO: Useless for now.
    }
}