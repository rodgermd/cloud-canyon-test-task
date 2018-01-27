<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 12:50
 */

namespace Tests\Unit\Validator;

use AppBundle\Entity\Product;
use AppBundle\Validator\ProductConstraint;
use AppBundle\Validator\ProductValidator;
use Mockery\Mock;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Tests\Bootstrap\AbstractUnitTestCase;

class ProductValidatorTest extends AbstractUnitTestCase
{
    /**
     * @param int   $type
     * @param array $expectFieldsViolate
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate(int $type, array $expectFieldsViolate)
    {
        $product = new Product();
        $validator = new ProductValidator();
        $context = $this->getValidatorContext();

        $validator->initialize($context);

        $product->setType($type);
        $builder = $this->getValidationBuilder();
        $context->shouldReceive('buildViolation')->andReturn($builder);
        $builder->shouldReceive('addViolation')->times(count($expectFieldsViolate));
        foreach ($expectFieldsViolate as $field) {
            $builder->shouldReceive('atPath')->withArgs([$field])->once()->andReturnSelf();
        }

        static::assertNull($validator->validate($product, $this->getConstraint()));
    }

    public function validateDataProvider(): array
    {
        return [
            [Product::TYPE_ONE, ['color', 'texture']],
            [Product::TYPE_TWO, ['width', 'height']],
        ];
    }

    public function testValidateSkipped()
    {
        $validator = new ProductValidator();
        $context = $this->getValidatorContext();
        $context->shouldReceive('buildViolation')->never();

        $validator->initialize($context);

        static::assertNull($validator->validate('some', $this->getConstraint()));
        static::assertNull($validator->validate(new Product(), new NotBlank()));
    }

    /**
     * @return Mock|ExecutionContextInterface
     */
    protected function getValidatorContext()
    {
        /** @var ExecutionContextInterface|Mock $context */
        $context = \Mockery::mock(ExecutionContextInterface::class);

        return $context;
    }

    protected function getValidationBuilder()
    {
        /** @var ConstraintViolationBuilderInterface|Mock $builder */
        $builder = \Mockery::mock(ConstraintViolationBuilderInterface::class);

        return $builder;
    }

    protected function getConstraint(): ProductConstraint
    {
        return new ProductConstraint();
    }
}