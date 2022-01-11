<?php

namespace App\Repository;

use App\Entity\PhysicalContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhysicalContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhysicalContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhysicalContent[]    findAll()
 * @method PhysicalContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhysicalContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhysicalContent::class);
    }

    // /**
    //  * @return PhysicalContent[] Returns an array of PhysicalContent objects
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
    public function findOneBySomeField($value): ?PhysicalContent
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
