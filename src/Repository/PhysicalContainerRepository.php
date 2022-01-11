<?php

namespace App\Repository;

use App\Entity\PhysicalContainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhysicalContainer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhysicalContainer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhysicalContainer[]    findAll()
 * @method PhysicalContainer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhysicalContainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhysicalContainer::class);
    }

    // /**
    //  * @return PhysicalContainer[] Returns an array of PhysicalContainer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhysicalContainer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
