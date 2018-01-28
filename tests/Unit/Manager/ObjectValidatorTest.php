<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:20
 */

namespace Tests\Unit\Manager;


use AppBundle\Manager\ObjectValidator;
use Mockery\Mock;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Bootstrap\AbstractUnitTestCase;

class ObjectValidatorTest extends AbstractUnitTestCase
{
    /**
     * @param bool $shouldViolate
     * @param bool $expectException
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate(bool $shouldViolate, bool $expectException)
    {
        $validator = $this->getValidatorMock();
        $object = new \stdClass();
        $expectation = $validator->shouldReceive('validate')->withArgs([$object, null, ['group']])->once();

        if ($shouldViolate) {
            $constraint = new ConstraintViolation('test message', 'template', [], 'root', 'name', '-1');
            $expectation->andReturn(new ConstraintViolationList([$constraint]));
        } else {
            $expectation->andReturn(new ConstraintViolationList([]));
        }

        if ($expectException) {
            static::expectException(ValidatorException::class);
            static::expectExceptionMessage('test message');
        }

        $objectValidator = new ObjectValidator($validator);
        static::assertNull($objectValidator->validate($object, ['group']));
    }

    public function validateDataProvider(): array
    {
        return [
            [true, true],
            [false, false]
        ];
    }

    /**
     * @return Mock|ValidatorInterface
     */
    protected function getValidatorMock()
    {
        /** @var ValidatorInterface|Mock $validator */
        $validator = \Mockery::mock(ValidatorInterface::class);

        return $validator;
    }
}