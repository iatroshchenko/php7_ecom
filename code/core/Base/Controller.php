<?php


namespace Core\Base;


abstract class Controller
{
    protected $route;

    public function getRoute () {
        return $this->route;
    }

    public function __construct(array $route)
    {
        $this->route = $route;
    }
}