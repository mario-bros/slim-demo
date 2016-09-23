<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;
use Slim\App;
use Slim\Container;
use SlimDemo\Controller\CommentController;
use SlimDemo\Exception\HttpException;

$appDir = __DIR__;
$configDir = $appDir.'/config';

$rootDir = realpath($appDir.'/..');
$cacheDir = $rootDir.'/var/cache';

$enviroment = $enviroment ?? 'prod';

$config = require $configDir.'/config.php';

$enviromentConfigPath = $configDir.'/config_'.$enviroment.'.php';
if (is_file($enviromentConfigPath)) {
    $config = array_replace_recursive($config, require $enviromentConfigPath);
}

$container = new Container($config);

$container->register(new ConsoleProvider(), [
    'console.cache' => $cacheDir,
]);

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

$app = new App($container);

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
