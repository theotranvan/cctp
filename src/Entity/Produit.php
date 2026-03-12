<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @UniqueEntity(
 * fields={"nom_produit"},
 * message="Cet élément existe déjà")
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $nom_produit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $modifiable;

    /**
     * @ORM\ManyToOne(targetEntity=Lot::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lot;

    /**
     * @ORM\ManyToOne(targetEntity=Systeme::class, inversedBy="produits")
     */
    private $systeme;

    /**
     * @ORM\ManyToMany(targetEntity=Cctp::class, inversedBy="produits")
     */
    private $cctp;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Lignedevis::class, mappedBy="produit")
     */
    private $lignedevis;

    /**
     * @ORM\OneToMany(targetEntity=Specification::class, mappedBy="produit")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")})
     */
    private $specifications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $unite;

    /**
     * @var integer $ordre
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity=DocFinal::class, mappedBy="produit")
     * @ORM\OrderBy({"localisation" = "ASC"})
     */
    private $docfinals;

    public function __construct()
    {
        $this->cctp = new ArrayCollection();
        $this->lignedevis = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->docfinals = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_produit;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getModifiable(): ?string
    {
        return $this->modifiable;
    }

    public function setModifiable(?string $modifiable): self
    {
        $this->modifiable = $modifiable;

        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

        return $this;
    }

    public function getSysteme(): ?Systeme
    {
        return $this->systeme;
    }

    public function setSysteme(?Systeme $systeme): self
    {
        $this->systeme = $systeme;

        return $this;
    }

    /**
     * @return Collection|Cctp[]
     */
    public function getCctp(): Collection
    {
        return $this->cctp;
    }

    public function addCctp(Cctp $cctp): self
    {
        if (!$this->cctp->contains($cctp)) {
            $this->cctp[] = $cctp;
        }

        return $this;
    }

    public function removeCctp(Cctp $cctp): self
    {
        $this->cctp->removeElement($cctp);

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Lignedevis[]
     */
    public function getLignedevis(): Collection
    {
        return $this->lignedevis;
    }

    public function addLignedevi(Lignedevis $lignedevi): self
    {
        if (!$this->lignedevis->contains($lignedevi)) {
            $this->lignedevis[] = $lignedevi;
            $lignedevi->setProduit($this);
        }

        return $this;
    }

    public function removeLignedevi(Lignedevis $lignedevi): self
    {
        if ($this->lignedevis->removeElement($lignedevi)) {
            // set the owning side to null (unless already changed)
            if ($lignedevi->getProduit() === $this) {
                $lignedevi->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Specification[]
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(Specification $specification): self
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications[] = $specification;
            $specification->setProduit($this);
        }

        return $this;
    }

    public function removeSpecification(Specification $specification): self
    {
        if ($this->specifications->removeElement($specification)) {
            // set the owning side to null (unless already changed)
            if ($specification->getProduit() === $this) {
                $specification->setProduit(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection|Docfinal[]
     */
    public function getDocfinals(): Collection
    {
        return $this->docfinals;
    }

    public function addDocfinal(DocFinal $docfinal): self
    {
        if (!$this->docfinals->contains($docfinal)) {
            $this->docfinals[] = $docfinal;
            $docfinal->setProduit($this);
        }

        return $this;
    }

    public function removeDocfinals(Specification $docfinal): self
    {
        if ($this->docfinals->removeElement($docfinal)) {
            // set the owning side to null (unless already changed)
            if ($docfinal->getProduit() === $this) {
                $docfinal->setProduit(null);
            }
        }

        return $this;
    }
}
