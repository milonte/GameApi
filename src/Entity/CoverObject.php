<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateCoverObjectAction;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
#[ApiResource(
    iri: 'http://schema.org/CoverObject',
    normalizationContext: ['groups' => ['cover_object:read']],
    itemOperations: [
        'get'
    ],
    collectionOperations: [
        'get',
        'post' => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Réservé aux ADMINs !",
            'controller' => CreateCoverObjectAction::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'cover_object_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        ]
        )]
        class CoverObject
        {
            
            
    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
        $this->games = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    private ?int $id = null;

    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['cover_object:read', 'read:Game:collection'])]
    public ?string $contentUrl = null;

    /**
     * @Vich\UploadableField(mapping="cover_object", fileNameProperty="filePath")
     */
    #[Assert\File(
        maxSize: "2048k",
        maxSizeMessage: "Fichier trop lourd ( > 2M )",
        mimeTypes: ['image/*'],
        mimeTypesMessage: "Seulement .jpg ou .png autorisés !",
        groups: ['cover_object_create']
    )]
    #[Assert\NotNull(groups: ['cover_object_create'])]
    public ?File $file = null;

    /**
     * @ORM\Column(nullable=true)
     */
    public ?string $filePath = null;

    /**
     * @ORM\Column(nullable=false)
     */
    #[Groups(['cover_object:read', 'read:Game:collection', 'cover_object_create'])]
    public ?string $slug = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[Groups(['cover_object:read', 'read:Game:collection'])]
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="cover")
     */
    private $games;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }
}