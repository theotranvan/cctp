<?php
namespace App\Entity;
use App\Repository\TypeusageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeusageRepository::class)]
class Typeusage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomUsage = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToOne(targetEntity: Catusage::class, inversedBy: 'typeusages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Catusage $catusage = null;

    #[ORM\OneToMany(targetEntity: Chantier::class, mappedBy: 'typeusage')]
    private Collection $chantiers;

    public function __construct()
    {
        $this->chantiers = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomUsage(): ?string { return $this->nomUsage; }
    public function setNomUsage(string $nomUsage): static { $this->nomUsage = $nomUsage; return $this; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $color): static { $this->color = $color; return $this; }
    public function getCatusage(): ?Catusage { return $this->catusage; }
    public function setCatusage(?Catusage $catusage): static { $this->catusage = $catusage; return $this; }
    public function getChantiers(): Collection { return $this->chantiers; }
    public function __toString(): string { return $this->nomUsage ?? ''; }
}
