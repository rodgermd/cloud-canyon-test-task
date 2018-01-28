<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 14:13
 */

namespace Tests\Unit\RequestConverter;

use AppBundle\Manager\ObjectValidator;
use AppBundle\RequestConverter\AbstractRequestConverter;
use Mockery\Mock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Tests\Bootstrap\AbstractUnitTestCase;
use Tests\Unit\RequestConverter\Model\TestRequestModel;

class AbstractRequestConverterTest extends AbstractUnitTestCase
{
    /**
     * @param array  $requestQuery
     * @param array  $requestRequest
     * @param string $expectedName
     *
     * @throws \ReflectionException
     * @dataProvider buildModelDataProvider
     *
     * @see          AbstractRequestConverter::buildModel
     */
    public function testBuildModel(array $requestQuery, array $requestRequest, string $expectedName)
    {
        $converter = $this->getConverter();
        $converter->shouldReceive('getModelClassName')->andReturn(TestRequestModel::class);

        $request = new Request($requestQuery, $requestRequest);
        /** @var TestRequestModel $model */
        $model = $this->invokeMethod($converter, 'buildModel', [$request]);

        static::assertEquals(TestRequestModel::class, get_class($model));
        static::assertEquals($expectedName, $model->getName());
    }

    public function buildModelDataProvider(): array
    {
        return [
            [['name' => 'query'], [], 'query'],
            [['name' => 'query'], ['name' => 'request'], 'query'],
            [[], ['name' => 'request'], 'request'],
        ];
    }

    /**
     * @see AbstractRequestConverter::supports()
     */
    public function testSupports()
    {
        $configuration = new ParamConverter([]);
        $configuration->setClass('name');

        $converter = $this->getConverter();
        $converter->shouldReceive('getModelClassName')->andReturn(TestRequestModel::class);

        static::assertFalse($converter->supports($configuration));
        $configuration->setClass(TestRequestModel::class);
        static::assertTrue($converter->supports($configuration));
    }

    /**
     * @see AbstractRequestConverter::apply
     */
    public function testApply()
    {
        $converter = $this->getConverter();
        $validator = $this->getValidator();
        $request = new Request();
        $paramBag = \Mockery::mock(ParameterBag::class);
        $paramConverter = new ParamConverter([]);

        $this->injectProperty($request, 'attributes', $paramBag);
        $this->injectProperty($converter, 'validator', $validator);
        $paramConverter->setName('name');

        $model = new TestRequestModel();
        $converter->shouldReceive('buildModel')->andReturn($model)->once();

        $paramBag->shouldReceive('set')->withArgs(['name', $model])->once();

        static::assertTrue($converter->apply($request, $paramConverter));
    }

    /**
     * @return AbstractRequestConverter|Mock
     */
    protected function getConverter()
    {
        /** @var AbstractRequestConverter|Mock $converter */
        $converter = \Mockery::mock(AbstractRequestConverter::class);
        $converter->makePartial();
        $converter->shouldAllowMockingProtectedMethods();

        return $converter;
    }

    /**
     * @return ObjectValidator|Mock
     */
    protected function getValidator()
    {
        /** @var ObjectValidator|Mock $validator */
        $validator = \Mockery::mock(ObjectValidator::class);
        $validator->shouldReceive('validate');

        return $validator;
    }
}