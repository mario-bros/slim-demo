<?php

$loader = require __DIR__.'/../app/autoload.php';

$enviroment = 'dev';

$app = require __DIR__ . '/../app/app.php';

$app->run();
