<?php

declare(strict_types=1);

namespace ExceptionCodesData;

enum EdgeCodes: int
{
    case Unique = 1726358001;
}

final class OwnCodeException extends \Exception
{
    public function __construct(string $message, private readonly int $errorCode = 0)
    {
        parent::__construct($message, $this->errorCode);
    }
}

final class Edge
{
    private const SHARED = 1726358003;

    public function firstConstant(): never
    {
        throw new \RuntimeException('a', self::SHARED);
    }

    public function secondConstant(): never
    {
        throw new \RuntimeException('b', self::SHARED);
    }

    public function enumValue(): never
    {
        throw new \RuntimeException('c', EdgeCodes::Unique->value);
    }

    public function spread(array $arguments): never
    {
        throw new \RuntimeException(...$arguments);
    }

    public function ownCodeParameter(): never
    {
        throw new OwnCodeException('skipped');
    }

    public function dynamic(?int $code): never
    {
        throw new \RuntimeException('dynamic', $code ?? 1726358005);
    }

    public function defaultCode(): never
    {
        throw new DefaultCodeException('uses the default code');
    }

    public function rethrow(\Throwable $previous): never
    {
        throw $previous;
    }

    public function nullableCode(): never
    {
        throw new NullableCodeException('skipped');
    }
}

final class DefaultCodeException extends \Exception
{
    public function __construct(string $message = '', int $code = 1726358010)
    {
        parent::__construct($message, $code);
    }
}

final class NullableCodeException extends \Exception
{
    public function __construct(string $message = '', ?int $code = null)
    {
        parent::__construct($message, $code ?? 0);
    }
}
