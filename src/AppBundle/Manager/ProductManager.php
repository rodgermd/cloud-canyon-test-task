<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 14:22
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Model\Request\ProductEditModel;
use AppBundle\Model\Request\ProductListModel;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    /** @var ProductRepository */
    protected $repository;
    /** @var ObjectValidator */
    protected $validator;
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(ProductRepository $repository, ObjectValidator $validator, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ProductListModel $listModel
     *
     * @return Product[]
     */
    public function getList(ProductListModel $listModel): array
    {
        return $this->repository->getList($listModel->getLimit(), $listModel->getOffset());
    }

    public function create(ProductEditModel $editModel): Product
    {
        $product = new Product();
        ObjectUpdater::update($product, $editModel);

        $this->validator->validate($product);
        $this->entityManager->persist($product);
        $this->entityManager->flush($product);

        return $product;
    }
}
