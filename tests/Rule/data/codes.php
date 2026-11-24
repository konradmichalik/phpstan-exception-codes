<?php

declare(strict_types=1);

namespace ExceptionCodesData;

final class HttpProblemException extends \Exception
{
    public function __construct(int $status, string $message = '') {
        parent::__construct($message, 0);
    }
}

final class Thrower
{
    public function missing(): never
    {
        throw new \RuntimeException('no code');
    }

    public function zero(): never
    {
        throw new \RuntimeException('zero', 0);
    }

    public function tooSmall(): never
    {
        throw new \RuntimeException('small', 1234);
    }

    public function tooLarge(): never
    {
        throw new \RuntimeException('large', 8800415279);
    }

    public function valid(): never
    {
        throw new \RuntimeException('valid', 1726357767);
    }

    public function named(): never
    {
        throw new \RuntimeException(code: 1726357768, message: 'named');
    }

    public function dynamic(\Throwable $previous): never
    {
        throw new \RuntimeException('dynamic', $previous->getCode(), $previous);
    }

    public function notInt(): never
    {
        throw new HttpProblemException(404, 'skipped');
    }
}
