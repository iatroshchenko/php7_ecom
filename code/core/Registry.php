<?php


namespace Core;


class Registry
{
    use SingletonTrait;

    private static $props = [];

    public static function getProperty ($key)
    {
        return self::$props[$key] ?? null;
    }

    public static function setProperty ($key, $val)
    {
        self::$props[$key] = $val;
    }

    public static function all ()
    {
        return self::$props;
    }
}