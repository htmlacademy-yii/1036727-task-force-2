<?php

function exceptionHandler($exception): void
{
    error_log($exception->__toString() . "\n");
}

function errorHandler($severity, $message, $file, $line): void
{
    throw new \ErrorException($message, 0, $severity, $file, $line);
}

function assertHandler($file, $line, $assertion, $message): void
{
    throw new \ErrorException($message, 0, E_WARNING, $file, $line);
}
