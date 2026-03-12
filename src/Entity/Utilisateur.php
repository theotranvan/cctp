<?php
namespace App\Entity;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse email est déjà utilisée.')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nomUser = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomUser = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Cctp::class, mappedBy: 'utilisateur')]
    private Collection $cctps;

    #[ORM\OneToMany(targetEntity: Devis::class, mappedBy: 'utilisateur')]
    private Collection $devis;

    public function __construct()
    {
        $this->cctps = new ArrayCollection();
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getUserIdentifier(): string { return (string) $this->email; }
    public function getRoles(): array { $roles = $this->roles; $roles[] = 'ROLE_USER'; return array_unique($roles); }
    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    public function eraseCredentials(): void {}
    public function getNomUser(): ?string { return $this->nomUser; }
    public function setNomUser(string $nomUser): static { $this->nomUser = $nomUser; return $this; }
    public function getPrenomUser(): ?string { return $this->prenomUser; }
    public function setPrenomUser(string $prenomUser): static { $this->prenomUser = $prenomUser; return $this; }
    public function isVerified(): bool { return $this->isVerified; }
    public function setIsVerified(bool $isVerified): static { $this->isVerified = $isVerified; return $this; }
    public function getCctps(): Collection { return $this->cctps; }
    public function getDevis(): Collection { return $this->devis; }
    public function getFullName(): string { return $this->prenomUser . ' ' . $this->nomUser; }
    public function __toString(): string { return $this->getFullName(); }
}
