<?php

use phpdk\org\json\JSON;
use Slim\Http\Request;
use Slim\Http\Response;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/', function (Request $request, Response $response, array $args) {

    $a = $request->getParam('a');
    $b = $request->getParam('b');
    $response->getBody()->write(JSON::encode([
        'result' => $a + $b,
    ]));
    return $response;
});
$app->run();