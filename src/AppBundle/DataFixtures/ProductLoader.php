<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 17:20
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductLoader extends Fixture
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product
                ->setName('product ONE ' . $i)
                ->setType(Product::TYPE_ONE)
                ->setTexture('texture ' . $i)
                ->setColor('color ' . $i);
            $manager->persist($product);
        }

        for ($i = 10; $i < 20; $i++) {
            $product = new Product();
            $product
                ->setName('product TWO ' . $i)
                ->setType(Product::TYPE_TWO)
                ->setWidth(mt_rand(0, 100))
                ->setHeight(mt_rand(0, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }
}