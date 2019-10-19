<?php


namespace Core\Base;

use Error;

class View
{
    protected $controller;
    protected $data;
    protected $viewFile;
    protected $layoutFile;

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
        if (isset($this->viewFile)) return $this->viewFile;

        $prefix = $this->controller->getRoute()['prefix'];
        $prefix = $prefix ? $prefix . '/' : '';
        $folder = $this->controller->getRoute()['controller'];
        $template = $this->controller->getTemplate();

        $path = VIEWS . '/' . $prefix . $folder . '/' . $template . '.php';
        $this->viewFile = $path;

        return $path;
    }

    public function getLayoutFilePath()
    {
        if (isset($this->layoutFile)) return $this->layoutFile;

        $layoutFileName = $this->controller->getLayout();
        $layout = LAYOUTS . '/' . $layoutFileName . '.php';
        $this->layoutFile = $layout;

        return $layout;
    }

    public function renderMeta()
    {
        $meta = $this->getController()->getMeta();
        $output = '';
        if (isset($meta)) {
            $output .= '<title>' . $meta->getTitle() . '</title>' . PHP_EOL
                . '<meta name="description"' . 'content="' . $meta->getDescription() . '">'
                . '<meta name="keywords"' . 'content="' . $meta->getKeywords() . '">';
        }
        echo $output;
    }

    public function render()
    {
        // check if template file exists
        $viewFile = $this->getTemplateFilePath();
        if (!file_exists($viewFile)) throw new Error('No such template file: ' . $viewFile);

        // check if layout file exists
        $layoutFile = $this->getLayoutFilePath();
        if (!file_exists($layoutFile)) throw new Error('No such layout file: ' . $layoutFile);

        // if everything ok, proceed with data
        if (!empty($this->data)) extract($this->data);

        // then render
        ob_start();
        require_once $viewFile;
        $content = ob_get_clean();
        require_once $layoutFile;
    }
}