<?php


namespace Core;


class Registry
{
    use SingletonTrait;

    private static $props = [];

    public static function setProp ($key, $val)
    {
        self::$props[$key] = $val;
    }

    public static function getProp ($key)
    {
        return self::$props[$key] ?? null;
    }

    public static function getProps ()
    {
        return self::$props;
    }
}