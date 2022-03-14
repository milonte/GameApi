<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublisherRepository::class)
 */
#[ApiResource]
class Publisher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, mappedBy="publishers")
     */
    private $games;

    /**
     * @ORM\ManyToMany(targetEntity=GameData::class, mappedBy="publishers")
     */
    private $gameData;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->gameData = new ArrayCollection();
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
            $game->addPublisher($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removePublisher($this);
        }

        return $this;
    }

    /**
     * @return Collection|GameData[]
     */
    public function getGameData(): Collection
    {
        return $this->gameData;
    }

    public function addGameData(GameData $gameData): self
    {
        if (!$this->gameData->contains($gameData)) {
            $this->gameData[] = $gameData;
            $gameData->addPublisher($this);
        }

        return $this;
    }

    public function removeGameData(GameData $gameData): self
    {
        if ($this->gameData->removeElement($gameData)) {
            $gameData->removePublisher($this);
        }

        return $this;
    }
}
