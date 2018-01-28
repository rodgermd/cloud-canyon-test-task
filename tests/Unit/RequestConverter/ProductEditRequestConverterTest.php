<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 14:54
 */

namespace Tests\Unit\RequestConverter;

use AppBundle\Manager\ObjectValidator;
use AppBundle\Model\Request\ProductListModel;
use AppBundle\RequestConverter\ProductListRequestConverter;
use Mockery\Mock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Tests\Bootstrap\AbstractUnitTestCase;

/**
 * Class ProductEditRequestConverterTest
 *
 * @package Tests\Unit\RequestConverter
 */
class ProductEditRequestConverterTest extends AbstractUnitTestCase
{
    public function testApply()
    {
        $converter = new ProductListRequestConverter($this->getValidator());
        $array = ['offset' => 10, 'limit' => 20];
        $request = new Request([], $array);
        $paramConverter = new ParamConverter([]);
        $paramConverter->setClass($converter->getModelClassName());
        $paramConverter->setName('modelName');
        static::assertTrue($converter->apply($request, $paramConverter));

        static::assertEquals(ProductListModel::fromArray($array), $request->attributes->get('modelName'));
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