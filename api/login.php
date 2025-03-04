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
$user = $userGateway->getByUsername($data['username']);


if ($user === false) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid authentication credentials']);
    exit;
}

if (!password_verify($data['password'], $user['password'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Invalid password credentials']);
    exit;
}
$payload = [
    'sub' => $user['id'],
    'username' => $user['username'],
    'name' => $user['name'],
    'exp' => time() + 3600
];

// $access_token =  base64_encode(json_encode($payload));
$codec = new JwtCode($_ENV['JWT_SECRET']);
$access_token = $codec->encode($payload);
echo json_encode(['message' => 'User login', 'api_key' => $user['api_key'], 'access_token' => $access_token]);
exit;