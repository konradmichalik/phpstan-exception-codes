<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes;

use PhpParser\Node;
use PhpParser\Node\Expr\Throw_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Throw_>
 */
final class ExceptionCodeRule implements Rule
{
    public function __construct(
        private readonly ExceptionCodeReader $reader,
        private readonly int $minimum,
        private readonly int $maximum,
    ) {}

    public function getNodeType(): string
    {
        return Throw_::class;
    }

    /**
     * @return list<RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $code = $this->reader->read($node, $scope);
        if ($code === null) {
            return [];
        }

        if ($code === 0) {
            return [
                RuleErrorBuilder::message('Exception is thrown without a code.')
                    ->identifier('exceptionCode.missing')
                    ->build(),
            ];
        }

        if ($code < $this->minimum || $code > $this->maximum) {
            return [
                RuleErrorBuilder::message(sprintf('Exception code %d is outside the range %d to %d.', $code, $this->minimum, $this->maximum))
                    ->identifier('exceptionCode.outOfRange')
                    ->build(),
            ];
        }

        return [];
    }
}
