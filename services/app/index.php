<?php

namespace app;

use app\calculator\CalculatorService;
use Slim\Http\Request;
use Slim\Http\Response;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/', function (Request $request, Response $response, array $args) {
    $function = $request->getQueryParam('f', null);

    $call = new CalculatorService(include __DIR__ .'/../../config/config.php');

    $result = null;
    if ($function) {
        $result = $call->execute($function);
    }

    $form = <<<HTML
<html>
<body>
<form action="/">
<input type="text" name="f" value="$function">
<button type="submit">send</button>

<p>$result</p>
</form>
</body>
</html>

HTML;

    $response->getBody()->write($form);
    return $response;
});
$app->run();