<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:04
 */

namespace Tests\Unit\Manager;

use AppBundle\Entity\Product;
use AppBundle\Manager\ObjectUpdater;
use AppBundle\Model\Request\ProductEditModel;
use Tests\Bootstrap\AbstractUnitTestCase;
use Tests\Unit\Manager\Model\SourceModel;
use Tests\Unit\Manager\Model\TargetNotWritableModel;
use Tests\Unit\Manager\Model\TargetWritableModel;

class ObjectUpdateTest extends AbstractUnitTestCase
{
    public function testUpdateProduct()
    {
        $source = new ProductEditModel();
        $target = new Product();

        $source
            ->setName('name')
            ->setColor('color')
            ->setType(1)
            ->setTexture('texture')
            ->setWidth(100)
            ->setHeight(200);

        ObjectUpdater::update($target, $source);

        static::assertEquals($source->getName(), $target->getName());
        static::assertEquals($source->getColor(), $target->getColor());
        static::assertEquals($source->getType(), $target->getType());
        static::assertEquals($source->getTexture(), $target->getTexture());
        static::assertEquals($source->getWidth(), $target->getWidth());
        static::assertEquals($source->getHeight(), $target->getHeight());
    }

    public function testWritable()
    {
        $source = new SourceModel();
        $target = new TargetWritableModel();

        ObjectUpdater::update($target, $source);

        static::assertEquals($source->public, $target->getPublic());
        static::assertEquals($source->getPrivateReadable(), $target->getPrivateReadable());
        static::assertNull($target->getPrivateNotReadable());
        static::assertNull($target->getProtected());
    }

    public function testNotWritable()
    {
        $source = new SourceModel();
        $target = new TargetNotWritableModel();

        ObjectUpdater::update($target, $source);

        static::assertNull($this->fetchProperty($target, 'public'));
        static::assertNull($this->fetchProperty($target, 'protected'));
        static::assertNull($this->fetchProperty($target, 'privateNotReadable'));
        static::assertNull($this->fetchProperty($target, 'privateReadable'));
    }
}