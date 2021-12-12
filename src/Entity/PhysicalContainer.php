<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhysicalContainerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhysicalContainerRepository::class)
 */
#[ApiResource]
class PhysicalContainer
{
    public const PHYSICAL_CONTAINERS = [
        'Cardboard',
        'Plastic',
        'None',
        'Other'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups("read:Platform:item")]
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=PlatformBaseContent::class, mappedBy="physicalContainer")
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
            $platformBaseContent->setPhysicalContainer($this);
        }

        return $this;
    }

    public function removePlatformBaseContent(PlatformBaseContent $platformBaseContent): self
    {
        if ($this->platformBaseContents->removeElement($platformBaseContent)) {
            // set the owning side to null (unless already changed)
            if ($platformBaseContent->getPhysicalContainer() === $this) {
                $platformBaseContent->setPhysicalContainer(null);
            }
        }

        return $this;
    }
}
