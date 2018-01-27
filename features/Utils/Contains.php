<?php declare(strict_types=1);

namespace BehatTests\Utils;

use PHPUnit\Framework\Assert as Assertions;
use PHPUnit\Framework\ExpectationFailedException;

class Contains
{
    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    public static function assertContains($expected, $actual)
    {
        if ($expected === "*") {
            return;
        }

        if ($actual === null && !empty($expected)) {
            throw new ExpectationFailedException(sprintf('Failed asserting that null matches expected "%s"', $expected));
        }

        if (is_array($expected) && !empty($expected)) {
            foreach ($expected as $key => $needle) {
                Assertions::assertArrayHasKey($key, $actual);
                self::assertContains($needle, $actual[$key]);
            }

            return;
        }

        if (is_string($expected) && mb_strpos($expected, '*') !== false) {
            $pattern = self::conventStringToRegexp($expected);
            Assertions::assertRegExp($pattern, (string) $actual);

            return;
        }

        Assertions::assertEquals($expected, $actual, 'JSON equality');
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private static function conventStringToRegexp(string $string): string
    {
        $string = str_replace('*', 'replace_pattern', $string);
        $string = preg_quote($string, '/');
        $string = str_replace('replace_pattern', '(.*)', $string);

        return '/^' . $string . '$/';
    }

    /**
     * @param $expected
     * @param $actual
     *
     * @return bool
     */
    public static function isContain($expected, $actual): bool
    {
        try {
            self::assertContains($expected, $actual);

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
