<?php declare(strict_types=1);

namespace BehatTests\Traits;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

trait PropertyAccessorTrait
{
    /** @var PropertyAccessor */
    private $accessor;

    /**
     * @return PropertyAccessor
     */
    public function getAccessor(): PropertyAccessor
    {
        if (!$this->accessor) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->accessor;
    }
}
