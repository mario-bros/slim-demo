<?php

use Slim\App;
use Slim\Container;

$appDir = __DIR__;
$cacheDir = realpath($appDir.'/../var/cache');
$configDir = realpath($appDir.'/../config');

$config = require $configDir.'/config.php';
$enviromentConfigPath = $configDir.'/config_'.($enviroment ?? 'prod').'.php';
if (is_file($enviromentConfigPath)) {
    $config = array_replace_recursive($config, require $enviromentConfigPath);
}

$container = new Container($config['settings']);

require $appDir.'/providers.php';

foreach ($config['thirdpartySettings'] as $key => $value) {
    $container[$key] = $value;
}

$app = new App($container);

require $appDir.'/controllers.php';
require $appDir.'/errorhandlers.php';

return $app;
