<?php

namespace Core;

use Core\App;
use Error;

/**
 * Class Route
 * @package Core
 */
class Route
{
    /**
     * Empty route params
     */
    const EMPTY_ROUTE = [
        'controller' => null,
        'template' => null,
        'action' => null,
        'prefix' => null
    ];

    /**
     * @var array
     */
    private static $routes = [];
    /**
     * @var array
     */
    private static $currentRoute = [];

    /**
     * @param $regexp
     * @param array $array
     */
    public static function add ($regexp, array $array = [])
    {
        self::$routes[$regexp] = $array;
    }

    /**
     * @return array
     */
    public static function all()
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getCurrentRoute()
    {
        return self::$currentRoute;
    }

    /**
     * @param $url
     */
    public static function handle($url)
    {
        $url = self::removeQueryString($url);
        $route = self::getRoute($url);
        if ($route) {
            self::$currentRoute = $route;
            self::direct();
        } else {
            throw new Error("This probably is not the page you're looking for ...", 404);
        }
    }

    /**
     * Directs user to action
     */
    public static function direct()
    {
        $currentRoute = self::$currentRoute;
        $controllerClass = CONTROLLERS_NAMESPACE . '\\'
            . ($currentRoute['prefix'] ? $currentRoute['prefix'] . '\\' : '')
            . ($currentRoute['controller'] ? $currentRoute['controller'] . 'Controller' : '');

        // creating controller instance
        $controller = App::instance()->container()->get($controllerClass);
        $action = $currentRoute['action'] . 'Action';

        // checking if method exists
        if (!method_exists($controller, $action)) throw new Error("Method wasn't found: $controllerClass:$action");

        // if so, rendering the view
        $controller->$action();
        $view = $controller->getView();
        $view->render();
    }

    /**
     * @param $path
     * @return array|bool
     * Parses route data from user request
     */
    public static function getRoute ($path)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match($pattern, $path, $matches)) {
                $matches = array_filter($matches, function ($item) {
                   return is_string($item);
                }, ARRAY_FILTER_USE_KEY);

                $routeIntersect = array_intersect_key($route, self::EMPTY_ROUTE);
                $matchesIntersect = array_intersect_key($matches, self::EMPTY_ROUTE);

                $result = array_merge($routeIntersect, $matchesIntersect);
                $result = array_merge(self::EMPTY_ROUTE, $result);

                $result['action'] = $result['action'] ?? 'index';
                $result['controller'] = ucwords($result['controller']);
                if (isset($result['prefix'])) $result['prefix'] = ucwords($result['prefix']);

                return $result;
            }
        }
        return false;
    }

    private static function removeQueryString($url)
    {
        $params = parse_url($url);
        return $params['path'] ?? '';
    }

}