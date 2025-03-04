<?php
class UserController
{
    public function __construct(
        private UserGateway $gateway
    ) {}

    public function processRequest(string $method, ?int $id): void
    {
        switch ($method) {
            case 'POST':
                echo 'create user';
                break;
            default:
                http_response_code(404);
                break;
        }
    }
}