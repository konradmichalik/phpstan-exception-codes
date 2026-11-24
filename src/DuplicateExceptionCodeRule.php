<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<CollectedDataNode>
 */
final class DuplicateExceptionCodeRule implements Rule
{
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * @return list<RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // PHPStan analyses a trait once per using class, so the same place can be collected repeatedly
        $locationsByCode = [];
        foreach ($node->get(ExceptionCodeCollector::class) as $file => $entries) {
            foreach ($entries as [$code, $line]) {
                $locationsByCode[$code][$file . ':' . $line] = [$file, $line];
            }
        }

        $errors = [];
        foreach ($locationsByCode as $code => $locations) {
            if (count($locations) < 2) {
                continue;
            }
            foreach ($locations as [$file, $line]) {
                $errors[] = RuleErrorBuilder::message(sprintf('Exception code %d is thrown in more than one place.', $code))
                    ->file($file)
                    ->line($line)
                    ->identifier('exceptionCode.duplicate')
                    ->build();
            }
        }

        return $errors;
    }
}
