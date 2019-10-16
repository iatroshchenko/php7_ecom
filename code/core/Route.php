<?php

namespace Core;

class Route
{
    private static $routes = [];
    private static $current = [];

    public static function add ($regexp, array $array = [])
    {
        self::$routes[$regexp] = $array;
    }

    public static function all ()
    {
        return self::$routes;
    }

    public static function current ()
    {
        return self::$current;
    }

    public static function direct ($path)
    {
        if (self::hasRoute($path)) {
            // TODO
        } else {
            throw new \Error("This probably is not the page you're looking for ...", 404);
        }
    }

    public static function hasRoute ($path)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match($pattern, $path, $matches)) {
                dump($matches);
                return true;
            }
        }
        return false;
    }

}