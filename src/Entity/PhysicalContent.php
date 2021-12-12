<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhysicalContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhysicalContentRepository::class)
 */
#[ApiResource]
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
