<?php
namespace App\Entity;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nomProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $modifiable = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $unite = null;

    #[Gedmo\SortablePosition]
    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\ManyToOne(targetEntity: Lot::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lot $lot = null;

    #[ORM\ManyToOne(targetEntity: Systeme::class, inversedBy: 'produits')]
    private ?Systeme $systeme = null;

    #[ORM\ManyToMany(targetEntity: Cctp::class, mappedBy: 'produits')]
    private Collection $cctps;

    #[ORM\OneToMany(targetEntity: Specification::class, mappedBy: 'produit')]
    private Collection $specifications;

    #[ORM\OneToMany(targetEntity: DocFinal::class, mappedBy: 'produit', orphanRemoval: true)]
    #[ORM\OrderBy(['localisation' => 'ASC'])]
    private Collection $docFinals;

    public function __construct()
    {
        $this->cctps = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->docFinals = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomProduit(): ?string { return $this->nomProduit; }
    public function setNomProduit(string $nomProduit): static { $this->nomProduit = $nomProduit; return $this; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): static { $this->title = $title; return $this; }
    public function getContent(): ?string { return $this->content; }
    public function setContent(string $content): static { $this->content = $content; return $this; }
    public function getModifiable(): ?string { return $this->modifiable; }
    public function setModifiable(?string $modifiable): static { $this->modifiable = $modifiable; return $this; }
    public function getUnite(): ?string { return $this->unite; }
    public function setUnite(?string $unite): static { $this->unite = $unite; return $this; }
    public function getOrdre(): ?int { return $this->ordre; }
    public function setOrdre(?int $ordre): static { $this->ordre = $ordre; return $this; }
    public function getLot(): ?Lot { return $this->lot; }
    public function setLot(?Lot $lot): static { $this->lot = $lot; return $this; }
    public function getSysteme(): ?Systeme { return $this->systeme; }
    public function setSysteme(?Systeme $systeme): static { $this->systeme = $systeme; return $this; }
    public function getCctps(): Collection { return $this->cctps; }
    public function getSpecifications(): Collection { return $this->specifications; }
    public function getDocFinals(): Collection { return $this->docFinals; }
    public function __toString(): string { return $this->nomProduit ?? ''; }
}
