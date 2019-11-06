<?php

define('ROOT', dirname(__DIR__));
define('VENDOR', ROOT . '/vendor');
require_once(VENDOR . '/autoload.php');

define('DEBUG', 1);
define('WEB', ROOT . '/public');
define('APP', ROOT . '/app');
define('CORE', ROOT . '/core');
define('STORAGE', ROOT . '/storage');
define('CONF', ROOT . '/config');
define('CACHE', STORAGE . '/cache');

// files
define('SERVICES_FILE', CONF . '/services.php');

// templates
define('RESOURCES', ROOT . '/resources');
define('VIEWS', RESOURCES . '/views');
define('LAYOUTS', VIEWS . '/layouts');
define('ERROR_TEMPLATES', VIEWS . '/error');

define('DEFAULT_LAYOUT', 'default');
define('DEFAULT_TEMPLATE', 'index');

// getting requested route
define('REQUESTED_ROUTE', trim($_SERVER['REQUEST_URI'], '/'));

// getting app path
$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$url = preg_replace("#[^/]+$#", '', $url);
$url = substr($url, 0, -1);

define('APP_URL', $url);
define('ADMIN_URL', APP_URL . '/admin');

// Set routes
define('ROUTES', ROOT . '/routes');
require_once(ROUTES . '/web.php');
require_once(ROUTES . '/default.php');

// Namespaces
define('CONTROLLERS_NAMESPACE', '\App\Controllers');