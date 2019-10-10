<?php

define('DEBUG', 1);
define('ROOT', dirname(__DIR__));
define('WEB', ROOT . '/public');
define('APP', ROOT . '/app');
define('VENDOR', ROOT . '/vendor');
define('CORE', ROOT . '/vendor/myshop/core');
define('LIBS', ROOT . '/vendor/myshop/core/libs');
define('CACHE', ROOT . '/storage/cache');
define('CONF', ROOT . '/config');
define('LAYOUT', 'default');

// getting app path
$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$url = preg_replace("#[^/]+$#", '', $url);
$url = substr($url, 0, -1);

define('APP_URL', $url);
define('ADMIN_URL', APP_URL . '/admin');

require_once(VENDOR . '/autoload.php');