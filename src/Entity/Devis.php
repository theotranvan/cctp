<?php
namespace App\Entity;
use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Chantier::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chantier $chantier = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\OneToMany(targetEntity: Lignedevis::class, mappedBy: 'devis', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $lignesDevis;

    public function __construct()
    {
        $this->lignesDevis = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $utilisateur): static { $this->utilisateur = $utilisateur; return $this; }
    public function getChantier(): ?Chantier { return $this->chantier; }
    public function setChantier(?Chantier $chantier): static { $this->chantier = $chantier; return $this; }
    public function getEntreprise(): ?Entreprise { return $this->entreprise; }
    public function setEntreprise(?Entreprise $entreprise): static { $this->entreprise = $entreprise; return $this; }
    public function getLignesDevis(): Collection { return $this->lignesDevis; }
    public function addLigneDevis(Lignedevis $ligneDevis): static { if (!$this->lignesDevis->contains($ligneDevis)) { $this->lignesDevis->add($ligneDevis); $ligneDevis->setDevis($this); } return $this; }
    public function removeLigneDevis(Lignedevis $ligneDevis): static { if ($this->lignesDevis->removeElement($ligneDevis)) { if ($ligneDevis->getDevis() === $this) { $ligneDevis->setDevis(null); } } return $this; }
    public function getTotalHT(): float { return array_sum(array_map(fn($l) => $l->getMontantHT(), $this->lignesDevis->toArray())); }
}
