<?php

return [
    'settings' => [
        'displayErrorDetails' => false,
    ],
    'thirdpartySettings' => [
        'debug' => false,
        'console.cache' => $cacheDir,
        'db.options' => [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'slim_demo',
            'user' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'orm.proxies_dir' => $cacheDir.'/doctrine/proxies',
        'orm.em.options' => [
            'mappings' => [
                [
                    'type' => 'annotation',
                    'namespace' => 'SlimDemo\Entity',
                    'path' => $appDir.'/Entity',
                    'use_simple_annotation_reader' => false,
                ],
            ],
        ],
    ],
];
