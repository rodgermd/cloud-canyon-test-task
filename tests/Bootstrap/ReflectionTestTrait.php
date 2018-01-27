<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 30.08.16
 * Time: 13:59
 */

namespace Tests\Bootstrap;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class ReflectionTestTrait
 *
 * @package Tests\Bootstrap\Traits
 */
trait ReflectionTestTrait
{
    /** @var  ReflectionClass */
    protected $reflection;

    /**
     * create new instance without constructor
     *
     * @param string $className
     *
     * @return object
     * @throws \ReflectionException
     */
    protected function getInstanceWithoutConstructor(string $className)
    {
        $this->reflection = new ReflectionClass($className);

        return $this->reflection->newInstanceWithoutConstructor();
    }

    /**
     * Invoke method.
     *
     * @param object $object
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function invokeMethod($object, string $method, array $args = [])
    {
        return $this->createAccessibleMethod($object, $method)->invokeArgs($object, $args);
    }

    /**
     * Create accessible method.
     *
     * @param object $object
     * @param string $method
     *
     * @return ReflectionMethod
     * @throws \ReflectionException
     */
    protected function createAccessibleMethod($object, string $method): ReflectionMethod
    {
        $method = new ReflectionMethod($object, $method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Inject property to object.
     *
     * @param object $object
     * @param string $name
     * @param mixed  $value
     *
     * @return ReflectionProperty
     * @throws \ReflectionException
     */
    protected function injectProperty($object, string $name, $value)
    {
        $property = new ReflectionProperty($object, $name);
        $property->setAccessible(true);
        $property->setValue($object, $value);

        return $property;
    }

    /**
     * Fetch property from object.
     *
     * @param object $object
     * @param string $name
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function fetchProperty($object, string $name)
    {
        $property = new ReflectionProperty($object, $name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
