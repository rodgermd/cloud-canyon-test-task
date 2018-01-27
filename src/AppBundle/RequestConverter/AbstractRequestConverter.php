<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 16:39
 */

namespace AppBundle\RequestConverter;

use AppBundle\Manager\ObjectValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequestConverter implements ParamConverterInterface
{
    /** @var ObjectValidator */
    protected $validator;

    public function __construct(ObjectValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $model = static::buildModel($request);
        $this->validator->validate($model);
        $request->attributes->set($configuration->getName(), $model);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function supports(ParamConverter $configuration)
    {
        return $this->getModelClassName() === $configuration->getClass();
    }

    public function buildModel(Request $request)
    {
        $params = array_merge($request->request->all(), $request->query->all());
        $model = call_user_func([$this->getModelClassName(), 'fromArray'], $params);

        return $model;
    }

    abstract public function getModelClassName(): string;
}