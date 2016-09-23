<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;
use Slim\App;
use Slim\Container;

$appDir = __DIR__;
$cacheDir = realpath($appDir.'/../var/cache');

$config = require $appDir.'/config.php';
$enviromentConfigPath = $appDir.'/config_'.$enviroment ?? 'prod'.'.php';
if (is_file($enviromentConfigPath)) {
    $config = array_replace_recursive($config, require $enviromentConfigPath);
}

$container = new Container($config['settings']);

$container->register(new ConsoleProvider());
$container->register(new DoctrineServiceProvider());
$container->register(new DoctrineOrmServiceProvider());
$container->register(new DoctrineOrmManagerRegistryProvider());

foreach ($config['thirdpartySettings'] as $key => $value) {
    $container[$key] = $value;
}

$app = new App($container);

require $appDir.'/controllers.php';
require $appDir.'/errorhandlers.php';

return $app;
