<?php

declare(strict_types=1);

namespace ExceptionCodesData;

trait ThrowingTrait
{
    public function fail(): never
    {
        throw new \RuntimeException('trait', 1726357999);
    }
}

final class FirstUser
{
    use ThrowingTrait;
}

final class SecondUser
{
    use ThrowingTrait;
}
