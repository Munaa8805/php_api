<?php

declare(strict_types=1);
require __DIR__ . '/bootstrap.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Allow: POST");
    exit;
}
$data = (array)json_decode(file_get_contents('php://input'), true);

if (!array_key_exists('username', $data) ||  !array_key_exists('password', $data)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing login credentials']);
    exit;
}
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$userGateway = new UserGateway($database);
$user = $userGateway->getUserRegister($data);



echo json_encode(['message' => 'User created', 'data' => $user]);
exit;