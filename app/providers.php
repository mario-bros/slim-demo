<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;

$container->register(new ConsoleProvider());
$container->register(new DoctrineServiceProvider());
$container->register(new DoctrineOrmServiceProvider());
$container->register(new DoctrineOrmManagerRegistryProvider());
