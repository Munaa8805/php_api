<?php

class ErrorHandler
{

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        throw new ErrorException($errstr, 0, $errno,  $errfile, $errline);
    }
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
