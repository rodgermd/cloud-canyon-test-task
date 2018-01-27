<?php declare(strict_types=1);

namespace AppBundle\Traits;

/**
 * Trait ClassConstantsTrait
 *
 * @package AppBundle\Traits
 */
trait ClassConstantsTrait
{
    /**
     * Gets named constants.
     *
     * @param string $name
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function namedConstants($name): array
    {
        $reflection = new \ReflectionClass(get_called_class());
        $constants = $reflection->getConstants();

        $keys = array_filter(
            array_keys($constants),
            function ($value) use ($name) {
                return preg_match('/^' . $name . '_/', $value);
            }
        );

        return array_intersect_key($constants, array_flip($keys));
    }
}
