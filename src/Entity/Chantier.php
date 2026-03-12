<?php

namespace App\Entity;

use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChantierRepository::class)
 */
class Chantier
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
    private $nom_chantier;

    /**
     * @ORM\ManyToOne(targetEntity=Typeusage::class, inversedBy="chantiers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeusage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $surface;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type_lgt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_lgt;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="chantier")
     */
    private $devis;


    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom_chantier;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomChantier(): ?string
    {
        return $this->nom_chantier;
    }

    public function setNomChantier(string $nom_chantier): self
    {
        $this->nom_chantier = $nom_chantier;

        return $this;
    }

    public function getTypeusage(): ?Typeusage
    {
        return $this->typeusage;
    }

    public function setTypeusage(?Typeusage $typeusage): self
    {
        $this->typeusage = $typeusage;

        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(?string $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getTypeLgt(): ?string
    {
        return $this->type_lgt;
    }

    public function setTypeLgt(?string $type_lgt): self
    {
        $this->type_lgt = $type_lgt;

        return $this;
    }

    public function getNbLgt(): ?int
    {
        return $this->nb_lgt;
    }

    public function setNbLgt(?int $nb_lgt): self
    {
        $this->nb_lgt = $nb_lgt;

        return $this;
    }

    /**
     * @return Collection|Devis[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setChantier($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getChantier() === $this) {
                $devi->setChantier(null);
            }
        }

        return $this;
    }

}
