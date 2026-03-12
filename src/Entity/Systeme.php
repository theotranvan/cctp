<?php

namespace App\Entity;

use App\Repository\SystemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SystemeRepository::class)
 * @UniqueEntity(
 * fields={"nom_systeme"},
 * message="Ce systeme existe déjà")
 */
class Systeme
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
    private $nom_systeme;

    /**
     * @ORM\ManyToMany(targetEntity=Lot::class, inversedBy="systemes")
     */
    private $lot;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="systeme")
     */
    private $produits;

    public function __construct()
    {
        $this->lot = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_systeme;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSysteme(): ?string
    {
        return $this->nom_systeme;
    }

    public function setNomSysteme(string $nom_systeme): self
    {
        $this->nom_systeme = $nom_systeme;

        return $this;
    }

    /**
     * @return Collection|Lot[]
     */
    public function getLot(): Collection
    {
        return $this->lot;
    }

    public function addLot(Lot $lot): self
    {
        if (!$this->lot->contains($lot)) {
            $this->lot[] = $lot;
        }

        return $this;
    }

    public function removeLot(Lot $lot): self
    {
        $this->lot->removeElement($lot);

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
            $produit->setSysteme($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getSysteme() === $this) {
                $produit->setSysteme(null);
            }
        }

        return $this;
    }
}
