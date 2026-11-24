<?php

declare(strict_types=1);

namespace ExceptionCodesData;

final class Boundaries
{
    public function minimum(): never
    {
        throw new \RuntimeException('minimum', 1000000000);
    }

    public function maximum(): never
    {
        throw new \RuntimeException('maximum', 2147483647);
    }

    public function belowMinimum(): never
    {
        throw new \RuntimeException('below', 999999999);
    }

    public function aboveMaximum(): never
    {
        throw new \RuntimeException('above', 2147483648);
    }
}
