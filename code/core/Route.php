<?php

namespace Core;

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
     * @param $path
     */
    public static function handle($path)
    {
        $route = self::getRoute($path);
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
        $currentRoute = self::getCurrentRoute();
        $controllerClass = CONTROLLERS_NAMESPACE . '\\'
            . ($currentRoute['prefix'] ? $currentRoute['prefix'] . '\\' : '')
            . ($currentRoute['controller'] ? $currentRoute['controller'] . 'Controller' : '');

        // check if class exists
        if (!class_exists($controllerClass)) throw new Error("No such controller: $controllerClass");

        // if so, setting the action
        $controller = new $controllerClass($currentRoute);
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

}