<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Throw_;
use PHPStan\Analyser\Scope;

/**
 * Reads the code of a thrown exception, but only when the exception takes an int $code as second constructor parameter.
 * Custom exceptions such as HttpProblemException(404, ...) therefore never produce a result.
 */
final class ExceptionCodeReader
{
    private const CODE_PARAMETER_POSITION = 1;

    /**
     * @return int|null 0 when the code is missing, null when the throw cannot be judged
     */
    public function read(Throw_ $throw, Scope $scope): ?int
    {
        $new = $throw->expr;
        if (!$new instanceof New_ || $new->isFirstClassCallable()) {
            return null;
        }

        $codeParameterName = $this->codeParameterName($new, $scope);
        if ($codeParameterName === null) {
            return null;
        }

        $codeArgument = $this->findCodeArgument($new, $codeParameterName);
        if ($codeArgument === false) {
            return null;
        }
        if ($codeArgument === null) {
            return 0;
        }

        $values = $scope->getType($codeArgument->value)->getConstantScalarValues();

        return count($values) === 1 && is_int($values[0]) ? $values[0] : null;
    }

    private function codeParameterName(New_ $new, Scope $scope): ?string
    {
        foreach ($scope->getType($new)->getObjectClassReflections() as $reflection) {
            if (!$reflection->hasConstructor()) {
                continue;
            }

            $parameters = $reflection->getConstructor()->getVariants()[0]->getParameters();
            $parameter = $parameters[self::CODE_PARAMETER_POSITION] ?? null;
            if ($parameter?->getType()->isInteger()->yes() === true) {
                return $parameter->getName();
            }
        }

        return null;
    }

    /**
     * @return Arg|false|null the argument, null when none was passed, false when the arguments cannot be resolved statically
     */
    private function findCodeArgument(New_ $new, string $codeParameterName): Arg|false|null
    {
        $position = 0;
        foreach ($new->getArgs() as $argument) {
            if ($argument->unpack) {
                return false;
            }
            if ($argument->name !== null) {
                if ($argument->name->toString() === $codeParameterName) {
                    return $argument;
                }
                continue;
            }
            if ($position === self::CODE_PARAMETER_POSITION) {
                return $argument;
            }
            ++$position;
        }

        return null;
    }
}
