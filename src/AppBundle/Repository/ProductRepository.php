<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 14:43
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ProductRepository extends EntityRepository
{
    /**
     * Gets product list
     *
     * @param int $limit
     * @param int $offset
     *
     * @return Product[]
     */
    public function getList(int $limit, int $offset): array
    {
        return $this->getListQueryBuilder($limit, $offset)->getQuery()->getResult();
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return QueryBuilder
     */
    public function getListQueryBuilder(int $limit, int $offset = 0): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb;
    }
}