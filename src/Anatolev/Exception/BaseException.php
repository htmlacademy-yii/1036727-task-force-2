<?php
namespace Anatolev\Exception;

class BaseException extends \Exception
{
    public static function exceptionHandler($exception): void
    {
        error_log($exception->__toString() . "\n");
    }

    public static function errorHandler($severity, $message, $file, $line): void
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public static function assertHandler($file, $line, $assertion, $message): void
    {
        throw new \ErrorException($message, 0, E_WARNING, $file, $line);
    }
}
