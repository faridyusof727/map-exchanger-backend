<?php
require_once('vendor/autoload.php');

use Controllers\DataController;
use josegonzalez\Dotenv\Loader;
use Libs\Response;
use Phalcon\Mvc\Micro;

// Load .env
$Loader = new Loader(__DIR__ . "/.env");
$Loader->parse();
$Loader->toEnv();

$app = new Micro();

// Allowing CORS
$app->before(function () use ($app) {
    $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';
    $app->response->setHeader("Access-Control-Allow-Origin", $origin)
        ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
        ->setHeader(
            "Access-Control-Allow-Headers",
            'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization'
        )
        ->setHeader("Access-Control-Allow-Credentials", true);

    $app->response->sendHeaders();
});

$app->options('/{catch:(.*)}', function () use ($app) {
    $app->response->setStatusCode(200, "OK")->send();
});

$app->post('/data', [new DataController(), 'getData']);

$app->notFound(function () {
    return Response::notFound();
});

try {
    $app->handle($_SERVER["REQUEST_URI"]);
} catch (\Exception $e) {
    $res = Response::fail($e->getMessage(), [], Response::INTERNAL_SERVER_ERROR_HTTP_CODE);
    $res->send();
}


