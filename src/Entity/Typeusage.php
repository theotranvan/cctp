<?php

namespace App\Entity;

use App\Repository\TypeusageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeusageRepository::class)
 */
class Typeusage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom_usage;

    /**
     * @ORM\ManyToOne(targetEntity=Catusage::class, inversedBy="typeusages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catusage;

    /**
     * @ORM\OneToMany(targetEntity=Chantier::class, mappedBy="typeusage")
     */
    private $chantiers;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $color;

    public function __construct()
    {
        $this->chantiers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_usage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUsage(): ?string
    {
        return $this->nom_usage;
    }

    public function setNomUsage(string $nom_usage): self
    {
        $this->nom_usage = $nom_usage;

        return $this;
    }

    public function getCatusage(): ?Catusage
    {
        return $this->catusage;
    }

    public function setCatusage(?Catusage $catusage): self
    {
        $this->catusage = $catusage;

        return $this;
    }

    /**
     * @return Collection|Chantier[]
     */
    public function getChantiers(): Collection
    {
        return $this->chantiers;
    }

    public function addChantier(Chantier $chantier): self
    {
        if (!$this->chantiers->contains($chantier)) {
            $this->chantiers[] = $chantier;
            $chantier->setTypeusage($this);
        }

        return $this;
    }

    public function removeChantier(Chantier $chantier): self
    {
        if ($this->chantiers->removeElement($chantier)) {
            // set the owning side to null (unless already changed)
            if ($chantier->getTypeusage() === $this) {
                $chantier->setTypeusage(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
