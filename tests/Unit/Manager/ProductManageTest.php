<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 13:36
 */

namespace Tests\Unit\Manager;

use AppBundle\Entity\Product;
use AppBundle\Manager\ObjectValidator;
use AppBundle\Manager\ProductManager;
use AppBundle\Model\Request\ProductEditModel;
use AppBundle\Model\Request\ProductListModel;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Mockery\Mock;
use Symfony\Component\Validator\ConstraintViolationList;
use Tests\Bootstrap\AbstractUnitTestCase;

class ProductManageTest extends AbstractUnitTestCase
{

    /**
     * @throws \ReflectionException
     * @see ProductManager::getList
     */
    public function testGetList()
    {
        $manager = $this->getManagerMock();
        $repository = $this->getRepositoryMock();
        $this->injectProperty($manager, 'repository', $repository);

        $repository->shouldReceive('getList')->withArgs([10, 20])->andReturn(['result']);
        $model = ProductListModel::fromArray(['offset' => 20, 'limit' => 10]);

        static::assertEquals(['result'], $manager->getList($model));
    }

    /**
     * @throws \ReflectionException
     * @see ProductManager::create()
     */
    public function testCreate()
    {
        $manager = $this->getManagerMock();
        $validator = $this->getValidatorMock();
        $entityManager = $this->getEntityManagerMock();

        $this->injectProperty($manager, 'validator', $validator);
        $this->injectProperty($manager, 'entityManager', $entityManager);

        $model = new ProductEditModel();
        $model->setHeight(100);
        $product = new Product();
        $product->setHeight(100);

        $validator->shouldReceive('validate')->withArgs([\Mockery::type(Product::class)])->andReturn(new ConstraintViolationList())->once();
        $entityManager->shouldReceive('persist')->withArgs([\Mockery::type(Product::class)])->once();
        $entityManager->shouldReceive('flush')->withArgs([\Mockery::type(Product::class)])->once();

        static::assertEquals($product, $manager->create($model));
    }

    /**
     * @return ProductRepository|Mock
     */
    protected function getRepositoryMock()
    {
        /** @var ProductRepository|Mock $repository */
        $repository = \Mockery::mock(ProductRepository::class);

        return $repository;
    }

    /**
     * @return ObjectValidator|Mock
     */
    protected function getValidatorMock()
    {
        /** @var ObjectValidator|Mock $validator */
        $validator = \Mockery::mock(ObjectValidator::class);

        return $validator;
    }

    /**
     * @return EntityManager|Mock
     */
    protected function getEntityManagerMock()
    {
        /** @var EntityManager|Mock $em */
        $em = \Mockery::mock(EntityManager::class);

        return $em;
    }

    /**
     * @return ProductManager|Mock
     */
    protected function getManagerMock()
    {
        /** @var ProductManager|Mock $manager */
        $manager = \Mockery::mock(ProductManager::class);
        $manager->makePartial();

        return $manager;
    }
}