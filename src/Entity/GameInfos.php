<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=GameInfosRepository::class)
 */
#[ApiResource]
class GameInfos
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
        Groups("read:Game:collection"),
        Length(min: 3, minMessage: "{{ limit }} caractères minimum !")
    ]
    private $title;


    /**
     * @ORM\ManyToMany(targetEntity=Platform::class)
     */
    #[Groups("read:Game:collection")]
    private $platforms;

    /**
     * @ORM\ManyToMany(targetEntity=Developer::class, inversedBy="gameInfos")
     */
    #[Groups("read:Game:collection")]
    private $developer;

    /**
     * @ORM\ManyToMany(targetEntity=Publisher::class, inversedBy="gameInfos")
     */
    #[Groups("read:Game:collection")]
    private $publishers;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups("read:Game:collection")]
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=CoverObject::class)
     */
    #[Groups("read:Game:collection")]
    private $cover;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="gameInfos")
     */
    #[Groups("read:Game:collection")]
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="gameInfos")
     */
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
            $game->setGameInfos($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getGameInfos() === $this) {
                $game->setGameInfos(null);
            }
        }

        return $this;
    }
}
