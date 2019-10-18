<?php


namespace Core\Base;


abstract class Controller
{
    protected $route;
    protected $view;
    protected $data;
    protected $template;

    public function getRoute()
    {
        return $this->route;
    }

    public function __construct(array $route)
    {
        $this->route = $route;
        $this->template = $route['template'] ?? DEFAULT_TEMPLATE;
        $this->data = [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData (array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getView()
    {
        return $this->view ?? $this->view = new View($this);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate(string $templateFileName)
    {
        $this->template = $templateFileName;
    }
}