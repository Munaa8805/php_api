<?php

class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        echo json_encode(
            [
                'messaage' => $exception->getMessage(),
                'code' => $exception->getCode(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
            ]
        );
    }
}