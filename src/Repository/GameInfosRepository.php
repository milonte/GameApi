<?php

namespace App\Repository;

use App\Entity\GameInfos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameInfos|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameInfos|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameInfos[]    findAll()
 * @method GameInfos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameInfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameInfos::class);
    }

    // /**
    //  * @return GameInfos[] Returns an array of GameInfos objects
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
    public function findOneBySomeField($value): ?GameInfos
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
