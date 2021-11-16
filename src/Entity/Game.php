<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
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
        "pagination_items_per_page" => 10
    ],
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:Game:collection"]
            ]
        ],
        "post" => [
            "denormalization_context" => [
                "groups" => ["write:Game:collection"]
            ]
        ],
    ],
    itemOperations: [
        "get",
        "put",
        "delete"
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['title' => 'DESC'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Game
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
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Platform::class, inversedBy="games", cascade={"persist"})
     */
    #[
        Groups(["read:Game:collection", "write:Game:collection"]),
        Valid()
    ]
    private $platforms;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
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
}
