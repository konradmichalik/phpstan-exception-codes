<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes\Tests\Rule;

use KonradMichalik\PhpStanExceptionCodes\DuplicateExceptionCodeRule;
use KonradMichalik\PhpStanExceptionCodes\ExceptionCodeCollector;
use KonradMichalik\PhpStanExceptionCodes\ExceptionCodeReader;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DuplicateExceptionCodeRule>
 */
final class DuplicateExceptionCodeRuleTest extends RuleTestCase
{
    public function testReportsEveryPlaceOfADuplicate(): void
    {
        $this->analyse([__DIR__ . '/data/duplicates.php'], [
            ['Exception code 1726357767 is thrown in 1 other place(s).', 11],
            ['Exception code 1726357767 is thrown in 1 other place(s).', 16],
        ]);
    }

    public function testDuplicatesAcrossFilesAreFound(): void
    {
        $this->analyse([__DIR__ . '/data/duplicates.php', __DIR__ . '/data/codes.php'], [
            ['Exception code 1726357767 is thrown in 2 other place(s).', 11],
            ['Exception code 1726357767 is thrown in 2 other place(s).', 16],
            ['Exception code 1726357767 is thrown in 2 other place(s).', 38],
        ]);
    }

    public function testThrowInTraitUsedTwiceIsNotADuplicate(): void
    {
        $this->analyse([__DIR__ . '/data/trait.php'], []);
    }

    protected function getRule(): Rule
    {
        return new DuplicateExceptionCodeRule();
    }

    protected function getCollectors(): array
    {
        return [new ExceptionCodeCollector(new ExceptionCodeReader())];
    }
}
