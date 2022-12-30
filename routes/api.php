<?php

use App\Controller\UserController;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function(RouteCollectorProxy $group) {
    $group->get('/users', UserController::class . ':index');   // .../api/users
    $group->get('/users/{id}', UserController::class . ':getUser');   // .../api/users/1
});