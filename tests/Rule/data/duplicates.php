<?php

declare(strict_types=1);

namespace ExceptionCodesData;

final class Duplicates
{
    public function first(): never
    {
        throw new \RuntimeException('a', 1726357767);
    }

    public function second(): never
    {
        throw new \RuntimeException('b', 1726357767);
    }

    public function unique(): never
    {
        throw new \RuntimeException('c', 1726357769);
    }
}
