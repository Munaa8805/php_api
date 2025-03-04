<?php


class Auth
{

    private int $user_id;
    public function __construct(
        private UserGateway $userGateway,
        private JWTCode $codec
    ) {}
    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER['HTTP_X_API_KEY'])) {
            http_response_code(400);
            echo json_encode(['message' => 'API key is required']);
            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];

        $user = $this->userGateway->getByAPIKey($api_key);

        if ($user === false) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid API key']);
            return false;
        }
        $this->user_id = $user['id'];
        return true;
    }

    public function getUserID(): int
    {
        return $this->user_id;
    }

    public function authencateAccessToken(): bool
    {
        if (! preg_match("/^Bearer\s+(.*)$/", $_SERVER["HTTP_AUTHORIZATION"], $matches)) {
            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

        try {
            $data = $this->codec->decode($matches[1]);
        } catch (Exception $e) {

            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
            return false;
        }

        $this->user_id = $data["sub"];

        return true;
    }
}