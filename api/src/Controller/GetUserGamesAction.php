<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GamesCollection;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *      "/users/{id}/games/",
 *      name="api_user_games",
 *      methods={"GET"},
 *      requirements={"id"="\d+"}
 * )
 */
class GetUserGamesAction extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $data)
    {
        $games = $this->entityManager->getRepository(GamesCollection::class)
            ->findUserGames($data);

        return $games;
    }
}
