<?php

use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

// instantiate app
$app = AppFactory::create();

require __DIR__ . "/../routes/api.php";