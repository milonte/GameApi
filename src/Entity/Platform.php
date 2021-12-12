<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=PlatformRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ],
    ],
    itemOperations: [
        "get",
        "put" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ],
        "delete" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ]
    ]
)]
class Platform
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups("read:Game:collection")]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(["read:Game:collection", "write:Game:collection"]),
        Length(min: 3, minMessage: "{{ limit }} caractères minimum !")
    ]
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="platform")
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setPlatform($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPlatform() === $this) {
                $game->setPlatform(null);
            }
        }

        return $this;
    }
}
