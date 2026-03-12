<?php
namespace App\Entity;
use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomChantier = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $surface = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeLgt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLgt = null;

    #[ORM\ManyToOne(targetEntity: Typeusage::class, inversedBy: 'chantiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Typeusage $typeusage = null;

    #[ORM\OneToMany(targetEntity: Devis::class, mappedBy: 'chantier')]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomChantier(): ?string { return $this->nomChantier; }
    public function setNomChantier(string $nomChantier): static { $this->nomChantier = $nomChantier; return $this; }
    public function getSurface(): ?string { return $this->surface; }
    public function setSurface(?string $surface): static { $this->surface = $surface; return $this; }
    public function getTypeLgt(): ?string { return $this->typeLgt; }
    public function setTypeLgt(?string $typeLgt): static { $this->typeLgt = $typeLgt; return $this; }
    public function getNbLgt(): ?int { return $this->nbLgt; }
    public function setNbLgt(?int $nbLgt): static { $this->nbLgt = $nbLgt; return $this; }
    public function getTypeusage(): ?Typeusage { return $this->typeusage; }
    public function setTypeusage(?Typeusage $typeusage): static { $this->typeusage = $typeusage; return $this; }
    public function getDevis(): Collection { return $this->devis; }
    public function __toString(): string { return $this->nomChantier ?? ''; }
}
