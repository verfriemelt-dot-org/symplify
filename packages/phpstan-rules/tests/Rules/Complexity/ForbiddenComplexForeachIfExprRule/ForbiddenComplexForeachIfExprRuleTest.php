<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\Complexity\ForbiddenComplexForeachIfExprRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symplify\PHPStanRules\Rules\Complexity\ForbiddenComplexForeachIfExprRule;

/**
 * @extends RuleTestCase<ForbiddenComplexForeachIfExprRule>
 */
final class ForbiddenComplexForeachIfExprRuleTest extends RuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/SkipWithoutMethodOrStaticCall.php', []];
        yield [__DIR__ . '/Fixture/SkipWithMethodCallWithoutParameter.php', []];
        yield [__DIR__ . '/Fixture/SkipWithStaticCallWithoutParameter.php', []];

        yield [__DIR__ . '/Fixture/SkipAssignBeforeIf.php', []];
        yield [__DIR__ . '/Fixture/SkipAssignAfterIf.php', []];
        yield [__DIR__ . '/Fixture/AssignInsideIf.php', [[ForbiddenComplexForeachIfExprRule::ERROR_MESSAGE, 12]]];

        yield [
            __DIR__ . '/Fixture/WithMethodCallWithParameter.php',
            [[ForbiddenComplexForeachIfExprRule::ERROR_MESSAGE, 16]],
        ];
        yield [
            __DIR__ . '/Fixture/WithStaticCallWithParameter.php',
            [[ForbiddenComplexForeachIfExprRule::ERROR_MESSAGE, 16]],
        ];

        yield [__DIR__ . '/Fixture/SkipTrinaryLogic.php', []];
        yield [__DIR__ . '/Fixture/SkipWithoutMethodCall.php', []];
        yield [__DIR__ . '/Fixture/SkipWithMethodCallWithoutParameter.php', []];
        yield [__DIR__ . '/Fixture/SkipNetteUtilsStringsMatchCall.php', []];
        yield [__DIR__ . '/Fixture/SkipMethodCallWithBooleanReturn.php', []];

        yield [__DIR__ . '/Fixture/SkipIfWithBoolean.php', []];
        yield [__DIR__ . '/Fixture/SkipIfWithBooleanFromExternalClass.php', []];

        yield [
            __DIR__ . '/Fixture/WithMethodCallWithParameterNotFromThis.php', [
                [ForbiddenComplexForeachIfExprRule::ERROR_MESSAGE, 17],
                [ForbiddenComplexForeachIfExprRule::ERROR_MESSAGE, 19],
            ],
        ];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenComplexForeachIfExprRule::class);
    }
}
