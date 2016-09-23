<?php

$loader = require __DIR__.'/../app/autoload.php';

$enviroment = 'prod';

$app = require __DIR__ . '/../app/app.php';

$app->run();
