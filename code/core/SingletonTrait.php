<?php


namespace Core;


trait SingletonTrait
{
    private static $instance;

    public static function getInstance () {
        return self::$instance ?? self::$instance = new self();
    }

    private function __construct()
    {
    }
}