<?php


class UserGateway
{
    private PDO $conn;
    public function __construct(
        Database $database
    ) {
        $this->conn = $database->getConnect();
    }

    public function getByAPIKey(string $api_key): array | false
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE api_key = :api_key');

        $stmt->execute(['api_key' => $api_key]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getByUsername(string $username): array | false
    {

        $sql = 'SELECT * FROM users WHERE username = :username';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getUserRegister(array $data): array | false
    {
        $sql = 'INSERT INTO users (name, username, password, api_key) VALUES (:name,:username, :password, :api_key)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':api_key', bin2hex(random_bytes(16)));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}