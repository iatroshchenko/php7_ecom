<?php

use Core\DB;
use Core\Cache;

return [
    'definitions' => [

    ],
    'singletons' => [
        DB::class => function () {
            return new DB();
        },
        Cache::class => function () {
            return new Cache();
        }
    ],
    'components' => [
        'db' => DB::class,
        'cache' => Cache::class
    ],
    'bindings' => [

    ]
];