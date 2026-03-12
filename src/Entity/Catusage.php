<?php

namespace App\Entity;

use App\Repository\CatusageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatusageRepository::class)
 */
class Catusage
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
    private $nom_catusage;

    /**
     * @ORM\OneToMany(targetEntity=Typeusage::class, mappedBy="catusage")
     */
    private $typeusages;

    public function __construct()
    {
        $this->typeusages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_catusage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCatusage(): ?string
    {
        return $this->nom_catusage;
    }

    public function setNomCatusage(string $nom_catusage): self
    {
        $this->nom_catusage = $nom_catusage;

        return $this;
    }

    /**
     * @return Collection|Typeusage[]
     */
    public function getTypeusages(): Collection
    {
        return $this->typeusages;
    }

    public function addTypeusage(Typeusage $typeusage): self
    {
        if (!$this->typeusages->contains($typeusage)) {
            $this->typeusages[] = $typeusage;
            $typeusage->setCatusage($this);
        }

        return $this;
    }

    public function removeTypeusage(Typeusage $typeusage): self
    {
        if ($this->typeusages->removeElement($typeusage)) {
            // set the owning side to null (unless already changed)
            if ($typeusage->getCatusage() === $this) {
                $typeusage->setCatusage(null);
            }
        }

        return $this;
    }
}
