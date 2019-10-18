<?php


namespace App\Controllers;

class MainController extends AppController
{
    public function indexAction()
    {
        $this->setTemplate('myview');
        $a = '123';
        $b = '223';
        $c = '243';
        $this->setData(compact(['a', 'b', 'c']));
    }
}