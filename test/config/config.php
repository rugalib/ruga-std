<?php


return [
    'dependencies' => [
        'factories' => [
            \Ruga\Std\Test\Facade\TestObject::class => function ($container) {
                return new \Ruga\Std\Test\Facade\TestObject();
            },

        ],
    ],
];

