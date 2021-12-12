<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlatformBaseContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlatformBaseContentRepository::class)
 */
#[ApiResource]
class PlatformBaseContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PhysicalSupport::class, inversedBy="platformBaseContents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $physicalSupport;

    /**
     * @ORM\ManyToOne(targetEntity=PhysicalContainer::class, inversedBy="platformBaseContents")
     */
    private $physicalContainer;

    /**
     * @ORM\ManyToMany(targetEntity=PhysicalContent::class, inversedBy="platformBaseContents")
     */
    private $physicalContent;

    public function __construct()
    {
        $this->physicalContent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhysicalSupport(): ?PhysicalSupport
    {
        return $this->physicalSupport;
    }

    public function setPhysicalSupport(?PhysicalSupport $physicalSupport): self
    {
        $this->physicalSupport = $physicalSupport;

        return $this;
    }

    public function getPhysicalContainer(): ?PhysicalContainer
    {
        return $this->physicalContainer;
    }

    public function setPhysicalContainer(?PhysicalContainer $physicalContainer): self
    {
        $this->physicalContainer = $physicalContainer;

        return $this;
    }

    /**
     * @return Collection|PhysicalContent[]
     */
    public function getPhysicalContent(): Collection
    {
        return $this->physicalContent;
    }

    public function addPhysicalContent(PhysicalContent $physicalContent): self
    {
        if (!$this->physicalContent->contains($physicalContent)) {
            $this->physicalContent[] = $physicalContent;
        }

        return $this;
    }

    public function removePhysicalContent(PhysicalContent $physicalContent): self
    {
        $this->physicalContent->removeElement($physicalContent);

        return $this;
    }
}
