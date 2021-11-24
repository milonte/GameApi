<?php

namespace App\Repository;

use App\Entity\GamesCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GamesCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamesCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamesCollection[]    findAll()
 * @method GamesCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GamesCollection::class);
    }

    public function findUserGames($value) {

        /* SELECT DISTINCT game.id, game.title
        FROM gameapi.games_collection AS collection
        JOIN gameapi.game as game
        WHERE collection.user_id = 5 AND collection.game_id = game.id
        ; */

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT game
            FROM App\Entity\GamesCollection AS collection
            JOIN App\Entity\Game as game
            WHERE collection.user = :val AND collection.game = game'
        )->setParameter('val', $value);

        // returns an array of Games objects
        return $query->getResult();
        
    }
}
