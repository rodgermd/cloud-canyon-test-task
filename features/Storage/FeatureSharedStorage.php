<?php declare(strict_types=1);

namespace BehatTests\Storage;

use BehatTest\Context\StorageContext;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @see StorageContext
 *
 * @package BehatTests\Storage
 */
class FeatureSharedStorage
{
    const PLACEHOLDER_PATTERN = '/{{(.*?)}}/';

    /** @var array */
    private static $storage = [];

    public static function clear(): void
    {
        self::$storage = [];
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return self::$storage;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $key)
    {
        return self::$storage[$key];
    }

    /**
     * @param string $variablePath
     *
     * @return mixed
     */
    public static function getByPath(string $variablePath)
    {
        $objectNodeList = explode('.', $variablePath, 2);
        $accessor = PropertyAccess::createPropertyAccessor();

        $object = self::get($objectNodeList[0]);

        if (count($objectNodeList) === 1) {
            return self::get($variablePath);
        }

        if (!$accessor->isReadable($object, $objectNodeList[1])) {
            throw new RuntimeException('Not found entity property for placeholder path ' . $objectNodeList[1]);
        }

        return $accessor->getValue($object, $objectNodeList[1]);
    }

    /**
     * @param string $variablePath
     *
     * @return string
     */
    public static function getStringByPath(string $variablePath): string
    {
        $variable = self::getByPath($variablePath);

        if (is_object($variable) || is_array($variable)) {
            throw new RuntimeException(sprintf('Storage variable for path "%s" not a string' . $variablePath));
        }

        return (string) $variable;
    }

    /**
     * @param string $key
     *
     * @param mixed $value
     */
    public static function set(string $key, $value): void
    {
        self::$storage[$key] = $value;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function replacePlaceholder(string $string): string
    {
        foreach (self::findStringPlaceholders($string) as $placeholder => $variablePath) {
            $string = str_replace($placeholder, self::getStringByPath($variablePath), $string);
        }

        return $string;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public static function replacePlaceholdersInArrayRecursive(array $parameters): array
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = self::replacePlaceholdersInArrayRecursive($value);
            } elseif (!(is_object($value) || is_bool($value))) {
                $parameters[$key] = self::replacePlaceholder((string)$value);
            }
        }

        return $parameters;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public static function findStringPlaceholders(string $string): array
    {
        $placeholderList = [];

        preg_match_all(self::PLACEHOLDER_PATTERN, $string, $matches);

        foreach ($matches[0] as $key => $placeholder) {
            $placeholderList[$placeholder] = $matches[1][$key];
        }

        return $placeholderList;
    }
}
