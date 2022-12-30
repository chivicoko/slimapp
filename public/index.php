<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . "/../bootstrap/app.php";

// routes
$app->get('/', function(Request $request, Response $response) {
    $response->getBody()->write("Slim Application running");
    return $response;
});

// $app->redirect('/customers', '/profiles', 301);

// $app->get('/profiles', function (Request $request, Response $response) {
//     $response->getBody()->write('Customers have been moved here');
//     return $response;
// });

// $app->get('/products/{id}', function (Request $request, Response $response, $args) {
//     $id = $args['id'];

//     $response->getBody()->write("This product has an id of $id");
//     return $response;
// });


$products = [
    1 => ['id' => 1, "name" => "Cell phone", "price" => "$150"],
    2 => ['id' => 2, "name" => "Car", "price" => "$4500"],
    3 => ['id' => 3, "name" => "Telephone", "price" => "$1500"],
];

$app->get('/products', function (Request $request, Response $response) use ($products) {
    $payload = json_encode($products);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/products/{id}', function (Request $request, Response $response, $args) use ($products) {
    $id = $args['id'];
    // check if element exists
    if (!array_key_exists($id, $products)) {
        $response->getBody()->write("Product not found");
        return $response->withStatus(404);
    }

    // if it exists...
    $product = $products[$id];

    $payload = json_encode($product);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});




// run app
$app->run();