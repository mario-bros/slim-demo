<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

if (!$loader = @include __DIR__.'/../vendor/autoload.php') {
    die('curl -s http://getcomposer.org/installer | php; php composer.phar install');
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
