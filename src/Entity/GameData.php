<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=GameDataRepository::class)
 */
#[ApiResource(
    attributes: [
        "pagination_items_per_page" => 10,
    ],
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:GameData:collection"]
            ]
        ],
        "post" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
            "denormalization_context" => [
                "groups" => ["write:GameData:collection"]
            ]
        ],
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:GameData:collection"]
            ]
        ],
        "put" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
            "denormalization_context" => [
                "groups" => ["put:GameData:collection"]
            ]
        ],
        "delete" =>  [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ]
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['updatedAt' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['updatedAt' => 'partial'])]
class GameData
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
    #[
        Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"]),
        Length(min: 3, minMessage: "{{ limit }} caractères minimum !")
    ]
    private $title;


    /**
     * @ORM\ManyToMany(targetEntity=Platform::class)
     */
    #[Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"])]
    private $platforms;

    /**
     * @ORM\ManyToMany(targetEntity=Developer::class, inversedBy="gameData")
     */
    #[Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"])]
    private $developer;

    /**
     * @ORM\ManyToMany(targetEntity=Publisher::class, inversedBy="gameData")
     */
    #[Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"])]
    private $publishers;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"])]
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=CoverObject::class)
     */
    #[Groups("read:GameData:collection")]
    private $cover;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="gameData")
     */
    #[Groups(["read:GameData:collection", "write:GameData:collection", "put:GameData:collection"])]
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="gameData")
     */
    #[Groups("read:GameData:collection")]
    private $games;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
        $this->developer = new ArrayCollection();
        $this->publishers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        $this->platforms->removeElement($platform);

        return $this;
    }

    /**
     * @return Collection|Developer[]
     */
    public function getDeveloper(): Collection
    {
        return $this->developer;
    }

    public function addDeveloper(Developer $developer): self
    {
        if (!$this->developer->contains($developer)) {
            $this->developer[] = $developer;
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): self
    {
        $this->developer->removeElement($developer);

        return $this;
    }

    /**
     * @return Collection|Publisher[]
     */
    public function getPublishers(): Collection
    {
        return $this->publishers;
    }

    public function addPublisher(Publisher $publisher): self
    {
        if (!$this->publishers->contains($publisher)) {
            $this->publishers[] = $publisher;
        }

        return $this;
    }

    public function removePublisher(Publisher $publisher): self
    {
        $this->publishers->removeElement($publisher);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCover(): ?CoverObject
    {
        return $this->cover;
    }

    public function setCover(?CoverObject $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

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
            $game->setGameData($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getGameData() === $this) {
                $game->setGameData(null);
            }
        }

        return $this;
    }
}
