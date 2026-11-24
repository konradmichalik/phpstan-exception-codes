<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes;

use PhpParser\Node;
use PhpParser\Node\Expr\Throw_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * @implements Collector<Throw_, array{int, int}>
 */
final class ExceptionCodeCollector implements Collector
{
    public function __construct(private readonly ExceptionCodeReader $reader) {}

    public function getNodeType(): string
    {
        return Throw_::class;
    }

    public function processNode(Node $node, Scope $scope): ?array
    {
        $code = $this->reader->read($node, $scope);

        // 0 means no code, which ExceptionCodeRule reports, so it must not count as a duplicate
        return $code === null || $code === 0 ? null : [$code, $node->getStartLine()];
    }
}
