<?php


namespace Core\Base;

use Core\Support\Meta;

abstract class Controller
{
    protected $route;
    protected $view;
    protected $data;
    protected $template;
    protected $layout = DEFAULT_LAYOUT;
    protected $meta;

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

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout(string $layoutFileName)
    {
        $this->layout = $layoutFileName;
    }

    public function setMeta(Meta $meta)
    {
        $this->meta = $meta;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}