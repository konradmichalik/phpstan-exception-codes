<?php

declare(strict_types=1);

namespace ExceptionCodesData;

final class Ignored
{
    public function legacy(): never
    {
        // @phpstan-ignore exceptionCode.missing (legacy exception without a code)
        throw new \RuntimeException('ignored');
    }
}
