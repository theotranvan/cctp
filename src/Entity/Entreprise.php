<?php
namespace App\Entity;
use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    public const ROLE_MOA = 'MOA';
    public const ROLE_MOE = 'MOE';
    public const ROLE_INSTALLATEUR = 'Installateur';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomEntreprise = null;

    #[ORM\Column(length: 50)]
    private ?string $numRueEntreprise = null;

    #[ORM\Column(length: 100)]
    private ?string $nomRueEntreprise = null;

    #[ORM\Column(length: 10)]
    private ?string $cpEntreprise = null;

    #[ORM\Column(length: 100)]
    private ?string $villeEntreprise = null;

    #[ORM\Column(length: 20)]
    private ?string $role = null;

    #[ORM\ManyToMany(targetEntity: Cctp::class, mappedBy: 'entreprises')]
    private Collection $cctps;

    #[ORM\OneToMany(targetEntity: Devis::class, mappedBy: 'entreprise')]
    private Collection $devis;

    public function __construct()
    {
        $this->cctps = new ArrayCollection();
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomEntreprise(): ?string { return $this->nomEntreprise; }
    public function setNomEntreprise(string $nomEntreprise): static { $this->nomEntreprise = $nomEntreprise; return $this; }
    public function getNumRueEntreprise(): ?string { return $this->numRueEntreprise; }
    public function setNumRueEntreprise(string $numRueEntreprise): static { $this->numRueEntreprise = $numRueEntreprise; return $this; }
    public function getNomRueEntreprise(): ?string { return $this->nomRueEntreprise; }
    public function setNomRueEntreprise(string $nomRueEntreprise): static { $this->nomRueEntreprise = $nomRueEntreprise; return $this; }
    public function getCpEntreprise(): ?string { return $this->cpEntreprise; }
    public function setCpEntreprise(string $cpEntreprise): static { $this->cpEntreprise = $cpEntreprise; return $this; }
    public function getVilleEntreprise(): ?string { return $this->villeEntreprise; }
    public function setVilleEntreprise(string $villeEntreprise): static { $this->villeEntreprise = $villeEntreprise; return $this; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getCctps(): Collection { return $this->cctps; }
    public function getDevis(): Collection { return $this->devis; }
    public function getAdresse(): string { return $this->numRueEntreprise . ' ' . $this->nomRueEntreprise . ', ' . $this->cpEntreprise . ' ' . $this->villeEntreprise; }
    public function __toString(): string { return $this->nomEntreprise ?? ''; }
}
