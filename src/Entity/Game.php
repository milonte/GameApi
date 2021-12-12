<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
#[ApiResource(
    attributes: [
        "enable_max_depth" => true
    ],
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:Game:collection"]
            ]
        ],
        "post" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
            "denormalization_context" => [
                "groups" => ["write:Game:collection"]
            ]
        ],
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:Game:collection"]
            ]
        ],
        "put" => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
            "denormalization_context" => [
                "groups" => ["put:Game:collection"]
            ]
        ],
        "delete" =>  [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ]
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['releaseDate' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['release' => 'partial'])]
class Game
{

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->developers = new ArrayCollection();
        $this->publishers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->gamesCollections = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups("read:Game:collection")]
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[Groups("read:Game:collection")]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[Groups("read:Game:collection")]
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Developer::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $developers;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $releaseDate;

    /**
     * @ORM\ManyToMany(targetEntity=Publisher::class, inversedBy="games")
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $publishers;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="games")
     */
    #[Groups("read:Game:collection")]

    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=CoverObject::class)
     */
    #[ApiProperty(iri: 'http://schema.org/image')]
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $cover;

    /**
     * @ORM\OneToMany(targetEntity=GamesCollection::class, mappedBy="game")
     */
    private $gamesCollections;

    /**
     * @ORM\ManyToOne(targetEntity=GameData::class)
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $gameData;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class, inversedBy="games")
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $platform;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read:Game:collection", "write:Game:collection", "put:Game:collection"])]
    private $isbn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Developer[]
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): self
    {
        if (!$this->developers->contains($developer)) {
            $this->developers[] = $developer;
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): self
    {
        $this->developers->removeElement($developer);

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

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
     * @return Collection|GamesCollection[]
     */
    public function getGamesCollections(): Collection
    {
        return $this->gamesCollections;
    }

    public function addGamesCollection(GamesCollection $gamesCollection): self
    {
        if (!$this->gamesCollections->contains($gamesCollection)) {
            $this->gamesCollections[] = $gamesCollection;
        }

        return $this;
    }

    public function removeGamesCollection(GamesCollection $gamesCollection): self
    {
        $this->gamesCollections->removeElement($gamesCollection);

        return $this;
    }

    public function getGameData(): ?GameData
    {
        return $this->gameData;
    }

    public function setGameData(?GameData $gameData): self
    {
        $this->gameData = $gameData;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }
}
