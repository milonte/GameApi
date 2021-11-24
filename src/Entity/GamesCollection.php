<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PostUserGamesAction;
use App\Repository\GamesCollectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesCollectionRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'controller' => PostUserGamesAction::class,
            "denormalization_context" => [
                "groups" => ["post:GamesCollection:collection"]
            ],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type'       => 'object',
                                'properties' =>
                                    [
                                        'user'        => ['type' => 'string'],
                                        'game_id' => ['type' => 'id'],
                                    ],
                            ],
                            'example' => [
                                'user'        => 'user@email.com',
                                'game_id' => '2',
                            ],
                        ],
                    ],
                ],
            ]
        ]
    ],
    itemOperations: [
        'delete' => [
            "security" => "is_granted('ROLE_ADMIN') or object.getUser() == user",
            "security_message" => "Vous n'êtes pas propriétaire de ce contenu !"
        ]
    ]
)]
class GamesCollection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gamesCollections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gamesCollections")
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
