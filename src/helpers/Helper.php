<?php

namespace anatolev\helpers;

abstract class Helper
{
    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        $method = "get{$name}";
        return static::$method($arguments[0]);
    }
}
