<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GamesCollection;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *      "/games_collection",
 *      name="games_collections_post",
 *      methods={"POST"},
 * )
 */
class PostUserGamesAction extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request)
    {
        $userEmail = json_decode($request->getContent())->user;
        $gameId = json_decode($request->getContent())->game_id;

        $game = $this->entityManager->getRepository(Game::class)
            ->findOneById($gameId);

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($userEmail);

        if (null === $game) {
            throw new JsonException('Game does not exist !');
        }

        if (null === $user) {
            throw new JsonException('User does not exist !');
        }

        if ($user !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new JsonException('Vous ne pouvez pas modifier ce contenu !');
        }

        $gamesCollection = new GamesCollection();
        $gamesCollection->setGame($game);
        $gamesCollection->setUser($user);

        $this->entityManager->persist($gamesCollection);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                "message" => "Jeu ajouté à l'utilisateur !",
                "user" => $user->getEmail(),
                "game" => $game->getTitle()
            ],
            200
        );
    }
}
