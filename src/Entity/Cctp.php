<?php
namespace App\Entity;
use App\Repository\CctpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CctpRepository::class)]
class Cctp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $nomOperation = null;

    #[ORM\Column]
    private ?int $numAffaire = null;

    #[ORM\Column]
    private bool $close = false;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'cctps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Lot::class, inversedBy: 'cctps')]
    #[ORM\JoinTable(name: 'cctp_lot')]
    private Collection $lots;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'cctps')]
    #[ORM\JoinTable(name: 'produit_cctp')]
    #[ORM\OrderBy(['ordre' => 'ASC'])]
    private Collection $produits;

    #[ORM\ManyToMany(targetEntity: Entreprise::class, inversedBy: 'cctps')]
    #[ORM\JoinTable(name: 'cctp_entreprise')]
    private Collection $entreprises;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getNomOperation(): ?string { return $this->nomOperation; }
    public function setNomOperation(string $nomOperation): static { $this->nomOperation = $nomOperation; return $this; }
    public function getNumAffaire(): ?int { return $this->numAffaire; }
    public function setNumAffaire(int $numAffaire): static { $this->numAffaire = $numAffaire; return $this; }
    public function isClose(): bool { return $this->close; }
    public function setClose(bool $close): static { $this->close = $close; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): static { $this->utilisateur = $utilisateur; return $this; }
    public function getLots(): Collection { return $this->lots; }
    public function addLot(Lot $lot): static { if (!$this->lots->contains($lot)) { $this->lots->add($lot); } return $this; }
    public function removeLot(Lot $lot): static { $this->lots->removeElement($lot); return $this; }
    public function getProduits(): Collection { return $this->produits; }
    public function addProduit(Produit $produit): static { if (!$this->produits->contains($produit)) { $this->produits->add($produit); } return $this; }
    public function removeProduit(Produit $produit): static { $this->produits->removeElement($produit); return $this; }
    public function getEntreprises(): Collection { return $this->entreprises; }
    public function addEntreprise(Entreprise $entreprise): static { if (!$this->entreprises->contains($entreprise)) { $this->entreprises->add($entreprise); } return $this; }
    public function removeEntreprise(Entreprise $entreprise): static { $this->entreprises->removeElement($entreprise); return $this; }
    public function __toString(): string { return sprintf('[%d] %s', $this->numAffaire ?? 0, $this->titre ?? ''); }
}
