<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetUserGamesAction;
use App\Controller\PostUserGamesAction;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_ADMIN') or object == user",
        "security_message" => "Vous n'êtes pas propriétaire de ce contenu !"
    ],
    collectionOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:User:collection"]
            ]
        ],
        "post"
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["read:User:item"]
            ]
        ],
        "put",
        "delete",
        'get_games' => [
            'method' => 'GET',
            'path' => '/users/{id}/games',
            "requirements" => ["id" => "\d+"],
            'controller' => GetUserGamesAction::class,
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["read:User:collection", "read:User:item", "post:GamesCollection:collection"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(["read:User:collection", "read:User:item"])]
    #[Email(message: "Email non valide !")]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(["read:User:collection", "read:User:item"])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Length(min: 6, minMessage: "Le mot de passe doit contenir au moins 6 caractères !")]
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=GamesCollection::class, mappedBy="user")
     */
    private $gamesCollections;

    public function __construct()
    {
        $this->gamesCollections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $gamesCollection->setUser($this);
        }

        return $this;
    }

    public function removeGamesCollection(GamesCollection $gamesCollection): self
    {
        if ($this->gamesCollections->removeElement($gamesCollection)) {
            // set the owning side to null (unless already changed)
            if ($gamesCollection->getUser() === $this) {
                $gamesCollection->setUser(null);
            }
        }

        return $this;
    }
}
