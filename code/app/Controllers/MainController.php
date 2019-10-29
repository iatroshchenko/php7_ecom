<?php

namespace App\Controllers;

use Core\Support\Meta;
use RedBeanPHP\R;
use Core\App;
use Core\DB;

class MainController extends AppController
{
    public function __construct(DB $db)
    {
    }

    public function indexAction()
    {
        $meta = new Meta([
            'title' => 'Index page yo-yo-yo',
            'description' => 'This is my index page',
            'keywords' => 'this, is, my, index, page'
        ]);
        $this->setMeta($meta);

        $this->setTemplate('myview');

        $posts = R::findAll('posts');
        $this->setData(compact('posts'));
    }
}