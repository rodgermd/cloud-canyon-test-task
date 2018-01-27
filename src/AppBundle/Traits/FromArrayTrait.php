<?php declare(strict_types=1);

namespace AppBundle\Traits;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Trait FromArrayTrait
 *
 * @package AppBundle\Traits
 */
trait FromArrayTrait
{
    public static function fromArray(array $data)
    {
        $object = new static();

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $key => $value) {
            if ($accessor->isWritable($object, $key)) {
                $accessor->setValue($object, $key, $value);
            }
        }

        return $object;
    }
}
