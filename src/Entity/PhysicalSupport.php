<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhysicalSupportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhysicalSupportRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:PhysicalSupport:collection"]
            ]
        ],
        "post" => [
            "denormalization_context" => [
                "groups" => ["write:PhysicalSupport:collection"]
            ],
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
        ]
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:PhysicalSupport:collection"]
            ]
        ],
        "put" => [
            "denormalization_context" => [
                "groups" => ["write:PhysicalSupport:collection"]
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
class PhysicalSupport
{
    public const PHYSICAL_SUPPORTS = [
        'Cartridge',
        'Disk',
        'Dematerialized',
        'Other'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Groups(["read:PhysicalSupport:collection", "write:PhysicalSupport:collection", "read:Platform:item"])]
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=PlatformBaseContent::class, mappedBy="physicalSupport")
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
            $platformBaseContent->setPhysicalSupport($this);
        }

        return $this;
    }

    public function removePlatformBaseContent(PlatformBaseContent $platformBaseContent): self
    {
        if ($this->platformBaseContents->removeElement($platformBaseContent)) {
            // set the owning side to null (unless already changed)
            if ($platformBaseContent->getPhysicalSupport() === $this) {
                $platformBaseContent->setPhysicalSupport(null);
            }
        }

        return $this;
    }
}
