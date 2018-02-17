<?php

namespace BungeePM\threads;


use BungeePM\utils\Debugger;
use BungeePM\utils\Logger;

class Thread extends \Thread
{

    public function run()
    {

    }

    public function start($options = 0)
    {
        parent::start($options);
    }

    public function kill()
    {
       Debugger::debug("Thread " . $this->getThreadId() . " has been stopped");
    }

    public function wait($timeout = 0)
    {
        Debugger::debug("Thread " . $this->getThreadId() . " is waiting for " . gmdate("i:s", $timeout));
        parent::wait($timeout);
    }


}