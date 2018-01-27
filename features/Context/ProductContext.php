<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 17:33
 */

namespace BehatTests\Context;


use AppBundle\Entity\Product;
use AppBundle\Manager\ObjectUpdater;
use AppBundle\Model\Request\ProductEditModel;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use BehatTests\Traits\DoctrineTrait;
use BehatTests\Traits\ResetDBTrait;

class ProductContext implements Context, KernelAwareContext
{
    use DoctrineTrait, ResetDBTrait;

    /**
     * @Given /^I have following products:$/
     */
    public function iHaveFollowingProducts(TableNode $table)
    {
        $entityManager = $this->getEntityManager();
        foreach ($table as $row)
        {
            $product = new Product();
            ObjectUpdater::update($product, ProductEditModel::fromArray($row));
            $entityManager->persist($product);
        }

        $entityManager->flush();
    }

    /**
     * @Given /^I empty products table/
     */
    public function iEmptyProductsTable()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Product::class);
        $repository->createQueryBuilder('p')->delete()->getQuery()->execute();
    }
}