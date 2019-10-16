<?php

use Core\Route;

// admin default routes
Route::add('#^admin$#', [
    'controller' => 'Admin',
    'action' => 'index',
    'prefix' => 'admin'
]);

Route::add('#^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$#');

// client default routes
Route::add('#^$#', [
    'controller' => 'Main',
    'action' => 'index'
]);

Route::add('#^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$#');