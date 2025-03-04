<?php

declare(strict_types=1);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);


require __DIR__ . '/bootstrap.php';

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resource = $parts[2];

$id = $parts[3] ?? null;

if ($resource != "tasks") {

    http_response_code(404);
    exit;
}




$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);



$userGateway = new UserGateway($database);

// var_dump(($_SERVER['HTTP_AUTHORIZATION']));
$codec = new JwtCode($_ENV['JWT_SECRET']);
$auth = new Auth($userGateway, $codec);

if (!$auth->authencateAccessToken()) {
    exit;
}
$user_id = $auth->getUserID();
// var_dump($user_id);
// exit;

// $database->getConnect();

$userController = new UserController($userGateway);
$gateway = new TaskGateway($database);
$controller = new TaskController($gateway, $user_id);
$userController->processRequest($_SERVER['REQUEST_METHOD'], $user_id);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);