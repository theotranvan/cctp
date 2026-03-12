<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $nom_categorie;

    /**
     * @ORM\OneToMany(targetEntity=Optionnel::class, mappedBy="categorie")
     */
    private $optionnels;

    public function __construct()
    {
        $this->optionnels = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->nom_categorie;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    /**
     * @return Collection|Optionnel[]
     */
    public function getOptionnels(): Collection
    {
        return $this->optionnels;
    }

    public function addOptionnel(Optionnel $optionnel): self
    {
        if (!$this->optionnels->contains($optionnel)) {
            $this->optionnels[] = $optionnel;
            $optionnel->setCategorie($this);
        }

        return $this;
    }

    public function removeOptionnel(Optionnel $optionnel): self
    {
        if ($this->optionnels->removeElement($optionnel)) {
            // set the owning side to null (unless already changed)
            if ($optionnel->getCategorie() === $this) {
                $optionnel->setCategorie(null);
            }
        }

        return $this;
    }

    
}
