<?php


class TaskGateway
{
    private PDO $conn;
    public function __construct(
        Database $database
    ) {
        $this->conn = $database->getConnect();
    }


    public function findAllForUser(int $user_id): array
    {

        $sql = 'SELECT * FROM tasks WHERE user_id = :user_id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool)$row['is_completed'];
            $data[] = $row;
        }
        return $data;
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFind(int $user_id, int $id): array | false
    {

        $sql = 'SELECT * FROM tasks WHERE user_id = :user_id AND id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $data['is_completed'] = (bool)$data['is_completed'];
        }
        return $data;
    }
    public function create(int $user_id, array $data): string
    {
        $sql = 'INSERT INTO tasks (name,priority, is_completed, user_id) VALUES (:name,:priority, :is_completed,:user_id)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
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

    public function update(int $user_id, int $id, array $data): int
    {

        $fields = [];


        if (array_key_exists('name', $data)) {
            $fields['name'] = [
                $data['name'],
                PDO::PARAM_STR
            ];
        }
        if (array_key_exists('priority', $data)) {
            $fields['priority'] = [
                $data['priority'],
                $data['priority'] ? PDO::PARAM_INT : PDO::PARAM_NULL
            ];
        }
        if (array_key_exists('is_completed', $data)) {
            $fields['is_completed'] = [
                $data['is_completed'],
                PDO::PARAM_BOOL
            ];
        }


        if (empty($fields)) {
            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value=:$value";
            }, array_keys($fields));
            // print_r($sets);

            $sql = "UPDATE tasks SET " . implode(",", $sets) . " WHERE id = :id AND user_id = :user_id";
            // echo $sql;
            // exit;
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }
            $stmt->execute();
            return $stmt->rowCount();
        }
    }

    public function delete(int $user_id, int $id): int
    {
        $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}