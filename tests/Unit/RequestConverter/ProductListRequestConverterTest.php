<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 14:54
 */

namespace Tests\Unit\RequestConverter;

use AppBundle\Manager\ObjectValidator;
use AppBundle\Model\Request\ProductEditModel;
use AppBundle\Model\Request\ProductListModel;
use AppBundle\RequestConverter\ProductEditRequestConverter;
use AppBundle\RequestConverter\ProductListRequestConverter;
use Mockery\Mock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Tests\Bootstrap\AbstractUnitTestCase;

/**
 * Class ProductListRequestConverterTest
 *
 * @package Tests\Unit\RequestConverter
 */
class ProductListRequestConverterTest extends AbstractUnitTestCase
{
    public function testApply()
    {
        $converter = new ProductEditRequestConverter($this->getValidator());
        $array = [
            'name'    => 'name',
            'color'   => 'red',
            'texture' => 'texture',
            'type'    => 1,
            'width'   => 100,
            'height'  => null,
        ];
        $request = new Request([], $array);
        $paramConverter = new ParamConverter([]);
        $paramConverter->setClass($converter->getModelClassName());
        $paramConverter->setName('modelName');
        static::assertTrue($converter->apply($request, $paramConverter));

        static::assertEquals(ProductEditModel::fromArray($array), $request->attributes->get('modelName'));
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