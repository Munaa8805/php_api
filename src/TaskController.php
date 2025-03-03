<?php

class TaskController
{
    public function __construct(
        private TaskGateway $gateway
    ) {}
    public function processRequest(string $method, ?string $id): void
    {
        if ($id == null) {
            if ($method == 'GET') {
                echo json_encode($this->gateway->findAll());
            } else if ($method == 'POST') {
                // print_r($_POST);
                $data = (array)json_decode(file_get_contents('php://input'), true);
                // var_dump($data);
                $errors = $this->getValidationErrors($data);
                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }
                $id = $this->gateway->create($data);
                // echo json_encode(['id' => $id]);
                $this->respondCreated($id);
            } else {
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {
            $task = $this->gateway->getFind((int)$id);
            if ($task == false) {
                $this->respondNotFound($id);
                return;
            }
            switch ($method) {
                case 'GET':
                    echo json_encode($task);
                    break;
                case 'PUT':
                    // print_r($_POST);
                    $data = (array)json_decode(file_get_contents('php://input'), true);
                    // var_dump($data);
                    $errors = $this->getValidationErrors($data, false);
                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }
                    $rows =  $this->gateway->update($id, $data);
                    echo json_encode(['rows' => $rows, "message" => "Task $id updated"]);
                    break;
                case 'DELETE':

                    $rows = $this->gateway->delete((int)$id);
                    echo json_encode(['rows' => $rows, "message" => "Task $id deleted"]);
                    break;
                default:
                    $this->respondMethodNotAllowed('GET, PUT, DELETE, POST');
            }
        }
    }
    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }
    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(['message' => "Task $id not found"]);
    }
    private function respondCreated(string $id): void
    {
        http_response_code(201);
        echo json_encode(['message' => "Task $id created", 'id' => $id]);
    }
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        if ($is_new && empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        if (!empty($data['priority'])) {
            if (filter_var($data['priority'], FILTER_VALIDATE_INT) === false) {
                $errors['priority'] = 'Priority is required';
            }
        }
        return $errors;
    }
    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(['errors' => $errors]);
    }
}
