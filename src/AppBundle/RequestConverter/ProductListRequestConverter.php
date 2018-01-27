<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 15:54
 */

namespace AppBundle\RequestConverter;


use AppBundle\Model\Request\ProductListModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductListRequestConverter extends AbstractRequestConverter
{
    public function getModelClassName(): string
    {
        return ProductListModel::class;
    }
}