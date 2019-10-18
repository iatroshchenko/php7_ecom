<?php


namespace App\Controllers;

use Core\Base\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        dd($this->route);
    }
}