<?php

declare(strict_types=1);

namespace KonradMichalik\PhpStanExceptionCodes\Tests\Rule;

use KonradMichalik\PhpStanExceptionCodes\ExceptionCodeReader;
use KonradMichalik\PhpStanExceptionCodes\ExceptionCodeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ExceptionCodeRule>
 */
final class ExceptionCodeRuleTest extends RuleTestCase
{
    public function testReportsMissingAndOutOfRangeCodes(): void
    {
        $this->analyse([__DIR__ . '/data/codes.php'], [
            ['Exception is thrown without a code.', 18],
            ['Exception is thrown without a code.', 23],
            ['Exception code 1234 is outside the range 1000000000 to 2147483647.', 28],
            ['Exception code 8800415279 is outside the range 1000000000 to 2147483647.', 33],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ExceptionCodeRule(new ExceptionCodeReader(), 1000000000, 2147483647);
    }
}
