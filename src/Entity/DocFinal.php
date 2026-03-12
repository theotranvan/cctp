<?php
namespace App\Entity;
use App\Repository\DocFinalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocFinalRepository::class)]
class DocFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;

    #[ORM\Column(nullable: true)]
    private ?int $cctpId = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $moyenne = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'docFinals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int { return $this->id; }
    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): static { $this->type = $type; return $this; }
    public function getQuantite(): ?string { return $this->quantite; }
    public function setQuantite(?string $quantite): static { $this->quantite = $quantite; return $this; }
    public function getLocalisation(): ?string { return $this->localisation; }
    public function setLocalisation(?string $localisation): static { $this->localisation = $localisation; return $this; }
    public function getCctpId(): ?int { return $this->cctpId; }
    public function setCctpId(?int $cctpId): static { $this->cctpId = $cctpId; return $this; }
    public function getMoyenne(): ?string { return $this->moyenne; }
    public function setMoyenne(?string $moyenne): static { $this->moyenne = $moyenne; return $this; }
    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): static { $this->produit = $produit; return $this; }
}
