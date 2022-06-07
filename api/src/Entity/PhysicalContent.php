<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhysicalContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhysicalContentRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:PhysicalContent:collection"]
            ]
        ],
        "post" => [
            "denormalization_context" => [
                "groups" => ["write:PhysicalContent:collection"]
            ],
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
        ]
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:PhysicalContent:collection"]
            ]
        ],
        "put" => [
            "denormalization_context" => [
                "groups" => ["write:PhysicalContent:collection"]
            ],
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ],
        "delete" =>  [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !"
        ]
    ]
)]
class PhysicalContent
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
    #[Groups(["read:PhysicalContent:collection", "write:PhysicalContent:collection", "read:Platform:item"])]
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=PlatformBaseContent::class, mappedBy="physicalContent")
     */
    private $platformBaseContents;

    public function __construct()
    {
        $this->platformBaseContents = new ArrayCollection();
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
     * @return Collection|PlatformBaseContent[]
     */
    public function getPlatformBaseContents(): Collection
    {
        return $this->platformBaseContents;
    }

    public function addPlatformBaseContent(PlatformBaseContent $platformBaseContent): self
    {
        if (!$this->platformBaseContents->contains($platformBaseContent)) {
            $this->platformBaseContents[] = $platformBaseContent;
            $platformBaseContent->addPhysicalContent($this);
        }

        return $this;
    }

    public function removePlatformBaseContent(PlatformBaseContent $platformBaseContent): self
    {
        if ($this->platformBaseContents->removeElement($platformBaseContent)) {
            $platformBaseContent->removePhysicalContent($this);
        }

        return $this;
    }
}
