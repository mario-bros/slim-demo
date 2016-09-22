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

$app->group('/comments', function () use ($app, $container) {
    $app->get('', [$container['slimdemo.comment.controller'], 'list']);
    $app->post('', [$container['slimdemo.comment.controller'], 'post']);
});

$app->group('/comments/{id}', function () use ($app, $container) {
    $app->get('', [$container['slimdemo.comment.controller'], 'get']);
    $app->patch('', [$container['slimdemo.comment.controller'], 'patch']);
    $app->delete('', [$container['slimdemo.comment.controller'], 'delete']);
});

return $app;
