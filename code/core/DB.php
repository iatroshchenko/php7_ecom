<?php


namespace Core;
use \RedBeanPHP\R as R;
use Error;

class DB
{
    use SingletonTrait;

    private function __construct()
    {
        $db = require_once CONF . '/db.php';
        R::setup($db['dsn'], $db['user'], $db['password']);
        if (!R::testConnection()) throw new Error('Cannot connect to database');
        R::freeze(true);
        R::debug(true, 1);
    }
}