<?php

use SlimDemo\Controller\CommentController;

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
