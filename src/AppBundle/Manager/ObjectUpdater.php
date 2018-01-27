<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 17:01
 */

namespace AppBundle\Manager;


use Symfony\Component\PropertyAccess\PropertyAccess;

class ObjectUpdater
{
    public static function update($targetObject, $sourceObject): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $sourceObjectReflection = new \ReflectionClass($sourceObject);
        foreach ($sourceObjectReflection->getProperties() as $sourceProperty) {
            $propertyName = $sourceProperty->getName();
            if ($propertyAccessor->isReadable($sourceObject, $propertyName) && $propertyAccessor->isWritable($targetObject, $propertyName)) {
                $propertyAccessor->setValue($targetObject, $propertyName, $propertyAccessor->getValue($sourceObject, $propertyName));
            }
        }
    }
}