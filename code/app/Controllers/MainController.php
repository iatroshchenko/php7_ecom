<?php


namespace App\Controllers;

use Core\Support\Meta;

class MainController extends AppController
{
    public function indexAction()
    {
        $meta = new Meta([
            'title' => 'Index page yo-yo-yo',
            'description' => 'This is my index page',
            'keywords' => 'this, is, my, index, page'
        ]);
        $this->setMeta($meta);

        $this->setTemplate('myview');
        $a = '123';
        $b = '223';
        $c = '243';
        $this->setData(compact(['a', 'b', 'c']));
    }
}