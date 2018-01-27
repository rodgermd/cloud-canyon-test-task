<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 16:45
 */

namespace AppBundle\RequestConverter;

use AppBundle\Model\Request\ProductEditModel;
use Symfony\Component\HttpFoundation\Request;

class ProductEditRequestConverter extends AbstractRequestConverter
{
    public function getModelClassName(): string
    {
        return ProductEditModel::class;
    }
}