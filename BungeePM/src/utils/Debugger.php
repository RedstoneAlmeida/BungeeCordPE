<?php
/**
 * Created by PhpStorm.
 * User: NewAdmin
 * Date: 17.02.2018
 * Time: 17:38
 */

namespace BungeePM\utils;


use BungeePM\BungeePE;

class Debugger
{
    /**
     * @param $debugText
     */
    public static function debug($debugText){
        if(BungeePE::$debugMode){
            echo Color::BLUE . strval($debugText);
        }
    }

}