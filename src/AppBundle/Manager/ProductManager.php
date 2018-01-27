<?php
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

class ProductManager
{
    /** @var ProductRepository */
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
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

        return $product;
    }
}