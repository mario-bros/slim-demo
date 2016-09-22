<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;
use Slim\App;
use SlimDemo\Controller\CommentController;

$appDir = __DIR__;
$rootDir = realpath($appDir. '/..');

$loader = require $rootDir . '/vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new App;

/** @var \Pimple\Container $container */
$container = $app->getContainer();

$container->register(new ConsoleProvider());

$container->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'slim_demo',
        'user'      => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
    ]
]);

$container->register(new DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => $rootDir . '/var/cache/doctrine/proxies',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'SlimDemo\Entity',
                'path' => $appDir.'/Entity',
                'use_simple_annotation_reader' => false
            ],
        ],
    ],
]);

$container->register(new DoctrineOrmManagerRegistryProvider());

$container['slimdemo.comment.controller'] = function() use ($container) {
    return new CommentController($container['doctrine']->getManager());
};

$app->get('/comments/{id}', [$container['slimdemo.comment.controller'], 'get']);

return $app;
