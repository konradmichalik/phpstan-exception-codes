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
final class ExceptionCodeRuleCustomRangeTest extends RuleTestCase
{
    public function testConfiguredRangeReplacesTheDefaults(): void
    {
        $this->analyse([__DIR__ . '/data/codes.php'], [
            ['Exception is thrown without a code.', 18],
            ['Exception is thrown without a code.', 23],
            ['Exception code 1234 is outside the range 2000 to 9999999999.', 28],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ExceptionCodeRule(new ExceptionCodeReader(), 2000, 9999999999);
    }
}
