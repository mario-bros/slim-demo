<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Pimple\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;
use Slim\App;
use SlimDemo\Controller\CommentController;
use SlimDemo\Exception\HttpException;

$appDir = __DIR__;
$rootDir = realpath($appDir.'/..');

$loader = require $rootDir.'/vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new App($config);

/** @var Container $container */
$container = $app->getContainer();

$container->register(new ConsoleProvider());

$container->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'slim_demo',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ],
]);

$container->register(new DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => $rootDir.'/var/cache/doctrine/proxies',
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
]);

$container->register(new DoctrineOrmManagerRegistryProvider());

$container['errorHandler'] = function () {
    return function (Request $request, Response $response, \Exception $exception) {
        $response = $response->withHeader('Content-Type', 'application/json');

        if ($exception instanceof HttpException) {
            $response = $response->withStatus($exception->getCode());
        } else {
            $response = $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['error' => $exception->getMessage()]));

        return $response;
    };
};

$container['notFoundHandler'] = function () {
    return function (Request $request, Response $response) {
        $response = $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['error' => 'Route not found']));

        return $response;
    };
};

$container['notAllowedHandler'] = function () {
    return function (Request $request, Response $response, array $methods) {
        $response = $response
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode(['error' => 'Route not found']));

        return $response;
    };
};

$container[CommentController::class] = function () use ($container) {
    return new CommentController($container['doctrine']->getManager());
};

$app->group('/comments', function () use ($app, $container) {
    $app->get('', CommentController::class.':list');
    $app->post('', CommentController::class.':post');
});

$app->group('/comments/{id}', function () use ($app, $container) {
    $app->get('', CommentController::class.':get');
    $app->put('', CommentController::class.':put');
    $app->delete('', CommentController::class.':delete');
});

return $app;
