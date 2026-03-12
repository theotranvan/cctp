<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
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
    private $nom_entreprise;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $num_rue_entreprise;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom_rue_entreprise;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $cp_entreprise;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ville_entreprise;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=Cctp::class, mappedBy="entreprise")
     */
    private $cctps;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="entreprise")
     */
    private $devis;



    public function __construct()
    {
        $this->cctps = new ArrayCollection();
        $this->devis = new ArrayCollection();
    }


    public function __tostring()
    {
        return $this->nom_entreprise;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nom_entreprise;
    }

    public function setNomEntreprise(string $nom_entreprise): self
    {
        $this->nom_entreprise = $nom_entreprise;

        return $this;
    }

    public function getNumRueEntreprise(): ?string
    {
        return $this->num_rue_entreprise;
    }

    public function setNumRueEntreprise(string $num_rue_entreprise): self
    {
        $this->num_rue_entreprise = $num_rue_entreprise;

        return $this;
    }

    public function getNomRueEntreprise(): ?string
    {
        return $this->nom_rue_entreprise;
    }

    public function setNomRueEntreprise(string $nom_rue_entreprise): self
    {
        $this->nom_rue_entreprise = $nom_rue_entreprise;

        return $this;
    }

    public function getCpEntreprise(): ?string
    {
        return $this->cp_entreprise;
    }

    public function setCpEntreprise(string $cp_entreprise): self
    {
        $this->cp_entreprise = $cp_entreprise;

        return $this;
    }

    public function getVilleEntreprise(): ?string
    {
        return $this->ville_entreprise;
    }

    public function setVilleEntreprise(string $ville_entreprise): self
    {
        $this->ville_entreprise = $ville_entreprise;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Cctp>
     */
    public function getCctps(): Collection
    {
        return $this->cctps;
    }

    public function addCctp(Cctp $cctp): self
    {
        if (!$this->cctps->contains($cctp)) {
            $this->cctps[] = $cctp;
            $cctp->addEntreprise($this);
        }

        return $this;
    }

    public function removeCctp(Cctp $cctp): self
    {
        if ($this->cctps->removeElement($cctp)) {
            $cctp->removeEntreprise($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setEntreprise($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getEntreprise() === $this) {
                $devi->setEntreprise(null);
            }
        }

        return $this;
    }

    


    
}
