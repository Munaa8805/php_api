<?php


class TaskGateway
{
    private PDO $conn;
    public function __construct(
        Database $database
    ) {
        $this->conn = $database->getConnect();
    }


    public function findAll(): array
    {
        $stmt = $this->conn->query('SELECT * FROM tasks');
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool)$row['is_completed'];
            $data[] = $row;
        }
        return $data;
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFind(int $id): array | false
    {
        $stmt = $this->conn->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $data['is_completed'] = (bool)$data['is_completed'];
        }
        return $data;
    }
    public function create(array $data): string
    {
        $sql = 'INSERT INTO tasks (name,priority, is_completed) VALUES (:name,:priority, :is_completed)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        if (empty($data['priority'])) {
            $stmt->bindValue(':priority', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':priority', $data['priority'], PDO::PARAM_INT);
        }
        $data['is_completed'] = $data['is_completed'] ?? false;

        $stmt->bindValue(':is_completed', $data['is_completed'], PDO::PARAM_BOOL);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}
