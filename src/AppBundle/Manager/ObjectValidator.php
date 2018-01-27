<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 17:10
 */

namespace AppBundle\Manager;


use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObjectValidator
{
    /** @var ValidatorInterface */
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($object, array $groups = []): void
    {
        $constraints = $this->validator->validate($object, null, $groups);

        if ($constraints->count()) {
            throw new ValidatorException($constraints->get(0)->getMessage());
        }
    }
}