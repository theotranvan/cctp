<?php
namespace App\Entity;
use App\Repository\LignedevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignedevisRepository::class)]
class Lignedevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $quantite = null;

    #[ORM\ManyToOne(targetEntity: Devis::class, inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(targetEntity: Specification::class, cascade: ['persist', 'remove'])]
    private ?Specification $specification = null;

    public function getId(): ?int { return $this->id; }
    public function getQuantite(): ?string { return $this->quantite; }
    public function setQuantite(string $quantite): static { $this->quantite = $quantite; return $this; }
    public function getDevis(): ?Devis { return $this->devis; }
    public function setDevis(?Devis $devis): static { $this->devis = $devis; return $this; }
    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): static { $this->produit = $produit; return $this; }
    public function getSpecification(): ?Specification { return $this->specification; }
    public function setSpecification(?Specification $specification): static { $this->specification = $specification; return $this; }
    public function getMontantHT(): float { if (!$this->specification || !$this->specification->getPrixUnitaire()) { return 0.0; } return (float) $this->quantite * (float) $this->specification->getPrixUnitaire(); }
}
