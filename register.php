<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
    $database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $conn = $database->getConnect();
    $sql = "INSERT INTO users (name, username, password, api_key) VALUES (:name, :username, :password, :api_key)";


    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':username', $_POST['username']);
    $stmt->bindValue(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
    $stmt->bindValue(':api_key', bin2hex(random_bytes(16)));
    $stmt->execute();
    echo json_encode(['message' => 'User created']);
    exit;
}
