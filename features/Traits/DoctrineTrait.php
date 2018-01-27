<?php declare(strict_types=1);

namespace BehatTests\Traits;

use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;

trait DoctrineTrait
{
    use KernelDictionary;

    /**
     * @return Registry
     */
    protected function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return EntityManager|object
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param string $persistentObjectName
     *
     * @return ObjectRepository
     */
    protected function getRepository(string $persistentObjectName): ObjectRepository
    {
        return $this->getDoctrine()->getRepository($persistentObjectName);
    }
}
