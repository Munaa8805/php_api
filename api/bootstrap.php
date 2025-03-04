<?php


require dirname(__DIR__) . '/vendor/autoload.php';
set_error_handler('ErrorHandler::handleError');
set_exception_handler('ErrorHandler::handleException');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Max-Age: 3600');
