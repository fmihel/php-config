<?php

namespace fmihel\config;

class Config{
    private static $config = null;

    public static function create(){
        if (self::$config === null)
            self::$config = new ConfigCore();
    }

    public static function get(...$param){
        return self::$config->get(...$param);
    }

    public static function define(string $name,$value){
        self::$config->define($name,$value);
    }

    public static function loadFromFile(string $file,$testTemplate=false){
        return self::$config->loadFromFile($file,$testTemplate);
    }

}

Config::create();


?>