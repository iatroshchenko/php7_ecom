<?php


namespace Core\Base;

use Error;

class View
{
    protected $controller;
    protected $data;
    protected $templateFilePath;

    public function getController()
    {
        return $this->controller;
    }

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->data = $controller->getData();
    }

    public function getTemplateFilePath()
    {
        if (isset($this->templateFilePath)) return $this->templateFilePath;

        $controllerObject = $this->getController();
        $prefix = $controllerObject->getRoute()['prefix'];
        $prefix = $prefix ? $prefix . '/' : '';
        $folder = $controllerObject->getRoute()['controller'];
        $template = $controllerObject->getTemplate();

        $path = VIEWS . '/' . $prefix . $folder . '/' . $template . '.php';
        $this->templateFilePath = $path;

        return $path;
    }

    public function render ()
    {
        // check if template file exists
        $pathToView = $this->getTemplateFilePath();
        if (!file_exists($pathToView)) throw new Error('No such template file: ' . $pathToView);

        // if so, get it's content
        ob_start();
        require_once $pathToView;
        $content = ob_get_clean();
        echo $content;

    }
}