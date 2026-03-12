<?php
namespace App\Entity;
use App\Repository\SpecificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecificationRepository::class)]
class Specification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $prixUnitaire = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'specifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int { return $this->id; }
    public function getMarque(): ?string { return $this->marque; }
    public function setMarque(?string $marque): static { $this->marque = $marque; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): static { $this->type = $type; return $this; }
    public function getPrixUnitaire(): ?string { return $this->prixUnitaire; }
    public function setPrixUnitaire(string $prixUnitaire): static { $this->prixUnitaire = $prixUnitaire; return $this; }
    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): static { $this->produit = $produit; return $this; }
}
