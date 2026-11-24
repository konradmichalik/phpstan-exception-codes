<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Throw_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Type\Type;

/**
 * Reads the code of a thrown exception, but only when the exception takes an int $code as second constructor parameter.
 * Custom exceptions such as HttpProblemException(404, ...) therefore never produce a result.
 */
final class ExceptionCodeReader
{
    private const CODE_PARAMETER_NAME = 'code';
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

        $codeParameter = $this->findCodeParameter($new, $scope);
        if ($codeParameter === null) {
            return null;
        }

        $arguments = $new->getArgs();
        foreach ($arguments as $argument) {
            if ($argument->unpack) {
                return null;
            }
        }

        $codeArgument = $this->findCodeArgument($arguments);
        if ($codeArgument !== null) {
            return $this->constantInt($scope->getType($codeArgument->value));
        }

        $default = $codeParameter->getDefaultValue();

        return $default === null ? 0 : $this->constantInt($default);
    }

    private function findCodeParameter(New_ $new, Scope $scope): ?ParameterReflection
    {
        foreach ($scope->getType($new)->getObjectClassReflections() as $reflection) {
            if (!$reflection->hasConstructor()) {
                continue;
            }

            $parameters = $reflection->getConstructor()->getVariants()[0]->getParameters();
            $parameter = $parameters[self::CODE_PARAMETER_POSITION] ?? null;
            if ($parameter?->getName() === self::CODE_PARAMETER_NAME && $parameter->getType()->isInteger()->yes()) {
                return $parameter;
            }
        }

        return null;
    }

    /**
     * @param array<Arg> $arguments
     */
    private function findCodeArgument(array $arguments): ?Arg
    {
        $position = 0;
        foreach ($arguments as $argument) {
            if ($argument->name === null) {
                if ($position === self::CODE_PARAMETER_POSITION) {
                    return $argument;
                }
                ++$position;
            } elseif ($argument->name->toString() === self::CODE_PARAMETER_NAME) {
                return $argument;
            }
        }

        return null;
    }

    private function constantInt(Type $type): ?int
    {
        $values = $type->getConstantScalarValues();

        return count($values) === 1 && is_int($values[0]) ? $values[0] : null;
    }
}
