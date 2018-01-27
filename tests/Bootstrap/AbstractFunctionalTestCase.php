<?php

namespace Tests\Bootstrap;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Mockery;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * For functional test with container without fixtures
 *
 *
 * Class AbstractFunctionalTestCase
 * @package Tests\Bootstrap
 */
abstract class AbstractFunctionalTestCase extends WebTestCase
{
    use ReflectionTestTrait;

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        ini_set('intl.default_locale', 'en_US');

        parent::setUpBeforeClass();
    }

    /**
     * On tear down.
     */
    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }


    protected function getValidator(): ValidatorInterface
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->getContainer()->get('validator');

        return $validator;
    }

    /**
     * @param ConstraintViolationListInterface|ConstraintViolation[] $list
     * @param string                                                 $path
     *
     * @return bool
     */
    protected function isViolationsContainPath(ConstraintViolationListInterface $list, string $path): bool
    {
        $paths = [];
        foreach ($list as $violation) {
            $paths[] = $violation->getPropertyPath();
        }

        return in_array($path, $paths);
    }
}
