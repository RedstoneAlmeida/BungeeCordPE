<?php

namespace BungeePM\utils;

class Logger{

    /**
     * @param string $text
     */
    public static function error(string $text){
         echo Color::RED . $text;
    }

    /**
     * @param string $text
     */
    public static function log(string $text){
        echo Color::YELLOW . $text;
    }

    /**
     * @param string $text
     */
    public static  function logActionFinish(string $text){
        echo Color::GREEN . $text;
    }
}