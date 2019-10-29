<?php

use Core\DB;

return [
    'definitions' => [

    ],
    'singletons' => [
        DB::class => function () {
            return new DB();
        }
    ],
    'components' => [
        'db' => DB::class
    ],
    'bindings' => [

    ]
];