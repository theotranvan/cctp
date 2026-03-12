<?php
namespace App\Entity;
use App\Repository\SystemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SystemeRepository::class)]
#[UniqueEntity(fields: ['nomSysteme'], message: 'Ce nom de système existe déjà.')]
class Systeme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nomSysteme = null;

    #[ORM\ManyToMany(targetEntity: Lot::class, mappedBy: 'systemes')]
    private Collection $lots;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'systeme')]
    private Collection $produits;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomSysteme(): ?string { return $this->nomSysteme; }
    public function setNomSysteme(string $nomSysteme): static { $this->nomSysteme = $nomSysteme; return $this; }
    public function getLots(): Collection { return $this->lots; }
    public function getProduits(): Collection { return $this->produits; }
    public function __toString(): string { return $this->nomSysteme ?? ''; }
}
