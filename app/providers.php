<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Saxulum\Console\Provider\ConsoleProvider;
use Saxulum\DoctrineOrmManagerRegistry\Provider\DoctrineOrmManagerRegistryProvider;
use Silex\Provider\DoctrineServiceProvider;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$container->register(new ConsoleProvider());
$container->register(new DoctrineServiceProvider());
$container->register(new DoctrineOrmServiceProvider());
$container->register(new DoctrineOrmManagerRegistryProvider());
