<?php

declare(strict_types=1);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
set_error_handler('ErrorHandler::handleError');
set_exception_handler('ErrorHandler::handleException');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resource = $parts[2];

$id = $parts[3] ?? null;

if ($resource != "tasks") {

    http_response_code(404);
    exit;
}


header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Max-Age: 3600');
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
// $database->getConnect();
$gateway = new TaskGateway($database);
$controller = new TaskController($gateway);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);
