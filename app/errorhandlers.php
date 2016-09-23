<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SlimDemo\Exception\HttpException;

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
