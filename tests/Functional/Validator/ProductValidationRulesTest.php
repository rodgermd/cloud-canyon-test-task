<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 13:43
 */

namespace Tests\Functional\Validator;

use AppBundle\Entity\Product;
use Doctrine\Common\Util\Inflector;
use Tests\Bootstrap\AbstractFunctionalTestCase;

class ProductValidationRulesTest extends AbstractFunctionalTestCase
{
    /**
     * @param int|null    $type
     * @param bool        $expectViolation
     *
     * @dataProvider typeDataProvider
     */
    public function testType($type, bool $expectViolation)
    {
        $product = new Product();
        $validator = $this->getValidator();

        static::assertNull($product->getType());

        if (!is_null($type)) {
            $product->setType($type);
        }

        static::assertEquals($expectViolation, $this->isViolationsContainPath($validator->validate($product), 'type'));
    }

    public function typeDataProvider(): array
    {
        return [
            [Product::TYPE_ONE, false],
            [Product::TYPE_TWO, false],
            [3, true],
        ];
    }

    /**
     * @param string      $property
     * @param mixed       $value
     * @param bool        $violates
     * @param null|string $expectException
     *
     * @dataProvider propertiesDataProvider
     */
    public function testProperties(string $property, $value, bool $violates, ?string $expectException = null)
    {
        $product = new Product();
        $validator = $this->getValidator();

        if ($expectException) {
            static::expectException($expectException);
        }

        $setter = Inflector::camelize("set_{$property}");
        $getter = Inflector::camelize("get_{$property}");

        static::assertNull(call_user_func([$product, $getter]));

        call_user_func([$product, $setter], $value);
        static::assertEquals($violates, $this->isViolationsContainPath($validator->validate($product), $property));
    }

    public function propertiesDataProvider(): array
    {
        return [
            ['name', 'test', false],
            ['name', 1, false],
            ['name', null, true],
            ['color', "test", false],
            ['color', 1, false],
            ['color', null, false],
            ['texture', "test", false],
            ['texture', 1, false],
            ['texture', null, false],
            ['width', "test", false, 'TypeError'],
            ['width', 1, false],
            ['width', null, false],
            ['width', -1, true],
            ['height', "test", false, 'TypeError'],
            ['height', 1, false],
            ['height', null, false],
            ['height', -1, true],
        ];
    }
}