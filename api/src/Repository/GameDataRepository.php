<?php

namespace App\Repository;

use App\Entity\GameData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameData|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameData|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameData[]    findAll()
 * @method GameData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameData::class);
    }

    // /**
    //  * @return GameData[] Returns an array of GameData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameData
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
