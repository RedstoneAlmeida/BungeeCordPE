<?php


namespace BungeePM\utils;


class BungeeConfig
{

    public $dataFolder;

    /**
     * BungeeConfig constructor.
     * @param $dataFolder
     *
     * Constructed by loader.php because of dataFolder
     */
    public function __construct($dataFolder)
    {
        $this->dataFolder = rtrim($dataFolder, "\\/") . "/";
    }

    public function get() : string{
          return yaml_parse($this->dataFolder . "/config.yml");
    }

}