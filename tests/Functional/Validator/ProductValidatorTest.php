<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 13:18
 */

namespace Tests\Functional\Validator;

use AppBundle\Entity\Product;
use Tests\Bootstrap\AbstractFunctionalTestCase;

class ProductValidatorTest extends AbstractFunctionalTestCase
{
    /**
     * @param int $type
     * @param array  $expectFieldsConstraints
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate(int $type, array $expectFieldsConstraints)
    {
        $validator = $this->getValidator();
        $product = new Product();
        $product->setType($type);

        $violations = $validator->validate($product);
        foreach ($expectFieldsConstraints as $expectFieldsConstraint) {
            static::assertTrue($this->isViolationsContainPath($violations, $expectFieldsConstraint));
        }
    }

    public function validateDataProvider(): array
    {
        return [
            [Product::TYPE_ONE, ['color', 'texture']],
            [Product::TYPE_TWO, ['width', 'height']],
        ];
    }

}