<?php

namespace Tests\Bootstrap;

use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * All unit tests must extends this class
 *
 * Class AbstractUnitTestCase
 * @package Tests\Bootstrap
 */
abstract class AbstractUnitTestCase extends TestCase
{
    use ReflectionTestTrait;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        ini_set('intl.default_locale', 'en_US');

        parent::setUpBeforeClass();
    }

    /**
     * On tear down
     */
    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }
}
