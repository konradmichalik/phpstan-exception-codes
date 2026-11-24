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
            ['Exception code 1726357767 is thrown in more than one place.', 11],
            ['Exception code 1726357767 is thrown in more than one place.', 16],
        ]);
    }

    public function testDuplicatesAcrossFilesAreFound(): void
    {
        $this->analyse([__DIR__ . '/data/duplicates.php', __DIR__ . '/data/codes.php'], [
            ['Exception code 1726357767 is thrown in more than one place.', 11],
            ['Exception code 1726357767 is thrown in more than one place.', 16],
            ['Exception code 1726357767 is thrown in more than one place.', 38],
        ]);
    }

    public function testThrowInTraitUsedTwiceIsNotADuplicate(): void
    {
        $this->analyse([__DIR__ . '/data/trait.php'], []);
    }

    public function testSharedConstantsAreDuplicatesToo(): void
    {
        $this->analyse([__DIR__ . '/data/edge.php'], [
            ['Exception code 1726358003 is thrown in more than one place.', 26],
            ['Exception code 1726358003 is thrown in more than one place.', 31],
        ]);
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
