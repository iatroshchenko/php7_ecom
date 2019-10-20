<?php

$db_params = [
    'HOST' => 'mysql',
    'DB_NAME' => 'test',
    'USER' => 'root',
    'PASSWORD' => 'example'
];

return [
    'dsn' => 'mysql:host=' . $db_params['HOST'] . ';dbname=' . $db_params['DB_NAME'],
    'user' => $db_params['USER'],
    'password' => $db_params['PASSWORD']
];