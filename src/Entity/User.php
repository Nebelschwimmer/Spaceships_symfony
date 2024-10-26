<?php

namespace App\Entity;
use App\Entity\Publication;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types\Types;
use Gedmo\Timestampable\Traits\TimestampableEntity;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $username = null;


    #[ORM\Column(length: 180)]
    private ?string $email = null;


    #[ORM\Column(length: 180, nullable: true)]
    private ?string $avatar = null;


    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;
    #[ORM\JoinTable(name: 'publications_to_user')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'publication_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Publication', cascade: ['persist'])]
    private ArrayCollection|PersistentCollection $publications;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function getPublications(): ArrayCollection|PersistentCollection
    {
        return $this->publications;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setPublications(ArrayCollection|PersistentCollection $publications): static
    {
        $this->publications = $publications;

        return $this;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
        }

        return $this;
    }
    public function findPublicationBySpaceShipId(int $spaceship_id): ?Publication
    {
        foreach ($this->publications as $publication) {
            if ($publication->getSpaceshipId() === $spaceship_id) {
                return $publication;
            }
        }
        return null;
    }
    public function updatePublication(Publication $publication): static
    {
        if ($this->publications->contains($publication)) {
            $this->publications->removeElement($publication);
            $this->publications->add($publication);
        }

        return $this;
    }
    public function removePublication(Publication $publication): static
    {
        if ($this->publications->contains($publication)) {
            $this->publications->removeElement($publication);
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
