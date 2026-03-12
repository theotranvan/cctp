<?php
namespace App\Entity;
use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[UniqueEntity(fields: ['nomLot'], message: 'Ce nom de lot existe déjà.')]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nomLot = null;

    #[ORM\ManyToMany(targetEntity: Systeme::class, inversedBy: 'lots')]
    #[ORM\JoinTable(name: 'systeme_lot')]
    private Collection $systemes;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'lot')]
    #[ORM\OrderBy(['ordre' => 'ASC'])]
    private Collection $produits;

    #[ORM\ManyToMany(targetEntity: Cctp::class, mappedBy: 'lots')]
    private Collection $cctps;

    public function __construct()
    {
        $this->systemes = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->cctps = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomLot(): ?string { return $this->nomLot; }
    public function setNomLot(string $nomLot): static { $this->nomLot = $nomLot; return $this; }
    public function getSystemes(): Collection { return $this->systemes; }
    public function addSysteme(Systeme $systeme): static { if (!$this->systemes->contains($systeme)) { $this->systemes->add($systeme); } return $this; }
    public function removeSysteme(Systeme $systeme): static { $this->systemes->removeElement($systeme); return $this; }
    public function getProduits(): Collection { return $this->produits; }
    public function getCctps(): Collection { return $this->cctps; }
    public function __toString(): string { return $this->nomLot ?? ''; }
}
