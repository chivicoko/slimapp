<?php

use App\Controller\TestUserController;
use App\Controller\UserController;
use App\Middleware\JsonParserMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function(RouteCollectorProxy $group) {
    $group->get('/users', UserController::class . ':index');   // .../api/users
    $group->get('/users/{id}', UserController::class . ':getUser');   // .../api/users/1
    $group->post('/users', UserController::class . ':create')->add(new JsonParserMiddleware());
    $group->put('/users/{id}', UserController::class . ':update')->add(new JsonParserMiddleware());
    $group->delete('/users/{id}', UserController::class . ':delete');

    // model route side (using MVC architecturel pattern)
    $group->get('/test-users', TestUserController::class . ':index');
    $group->get('/test-users/{id}', TestUserController::class . ':findById');
    $group->post('/test-users', TestUserController::class . ':create')->add(new JsonParserMiddleware());
    $group->put('/test-users/{id}', TestUserController::class . ':update')->add(new JsonParserMiddleware());
    $group->delete('/test-users/{id}', TestUserController::class . ':delete');
});