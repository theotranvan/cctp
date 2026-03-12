<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=DevisRepository::class)
 */
class Devis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Chantier::class, inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chantier;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_At;

    /**
     * @ORM\OneToMany(targetEntity=Lignedevis::class, mappedBy="devis")
     */
    private $lignedevis;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entreprise;



    public function __construct()
    {
        $this->lignedevis = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->chantier;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getChantier(): ?Chantier
    {
        return $this->chantier;
    }

    public function setChantier(?Chantier $chantier): self
    {
        $this->chantier = $chantier;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_At;
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
            $lignedevi->setDevis($this);
        }

        return $this;
    }

    public function removeLignedevi(Lignedevis $lignedevi): self
    {
        if ($this->lignedevis->removeElement($lignedevi)) {
            // set the owning side to null (unless already changed)
            if ($lignedevi->getDevis() === $this) {
                $lignedevi->setDevis(null);
            }
        }

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
    


}
