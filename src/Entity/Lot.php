<?php

namespace App\Entity;

use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LotRepository::class)
 * @UniqueEntity(
 * fields={"nom_lot"},
 * message="Ce lot existe déjà")
 */
class Lot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom_lot;

    /**
     * @ORM\ManyToMany(targetEntity=Systeme::class, mappedBy="lot")
     */
    private $systemes;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="lot")
     */
    private $produits;


    public function __construct()
    {
        $this->systemes = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_lot;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLot(): ?string
    {
        return $this->nom_lot;
    }

    public function setNomLot(string $nom_lot): self
    {
        $this->nom_lot = $nom_lot;

        return $this;
    }

    /**
     * @return Collection|Systeme[]
     */
    public function getSystemes(): Collection
    {
        return $this->systemes;
    }

    public function addSysteme(Systeme $systeme): self
    {
        if (!$this->systemes->contains($systeme)) {
            $this->systemes[] = $systeme;
            $systeme->addLot($this);
        }

        return $this;
    }

    public function removeSysteme(Systeme $systeme): self
    {
        if ($this->systemes->removeElement($systeme)) {
            $systeme->removeLot($this);
        }

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setLot($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getLot() === $this) {
                $produit->setLot(null);
            }
        }
        return $this;
    }

}
