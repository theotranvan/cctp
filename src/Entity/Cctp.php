<?php

namespace App\Entity;

use App\Repository\CctpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CctpRepository::class)
 */
class Cctp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $titre;


    /**
     * @ORM\ManyToMany(targetEntity=Lot::class)
     */
    private $lotCctp;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, mappedBy="cctp")
     * @ORM\OrderBy({"ordre" = "ASC" })
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_operation;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_At;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="cctps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $close;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, inversedBy="cctps")
     */
    private $entreprise;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumAffaire;


    public function __construct()
    {
        $this->lotCctp = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->entreprise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function __toString()
    {
        return $this->lotCctp;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }


    /**
     * @return Collection|Lot[]
     */
    public function getLotCctp(): Collection
    {
        return $this->lotCctp;
    }

    public function addLotCctp(Lot $lotCctp): self
    {
        if (!$this->lotCctp->contains($lotCctp)) {
            $this->lotCctp[] = $lotCctp;
        }

        return $this;
    }

    public function removeLotCctp(Lot $lotCctp): self
    {
        $this->lotCctp->removeElement($lotCctp);

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
            $produit->addCctp($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCctp($this);
        }

        return $this;
    }

    
    public function getNomOperation(): ?string
    {
        return $this->nom_operation;
    }

    public function setNomOperation(string $nom_operation): self
    {
        $this->nom_operation = $nom_operation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_At;
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

    public function getClose(): ?bool
    {
        return $this->close;
    }

    public function setClose(bool $close): self
    {
        $this->close = $close;

        return $this;
    }

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprise(): Collection
    {
        return $this->entreprise;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprise->contains($entreprise)) {
            $this->entreprise[] = $entreprise;
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        $this->entreprise->removeElement($entreprise);

        return $this;
    }

    public function getNumAffaire(): ?int
    {
        return $this->NumAffaire;
    }

    public function setNumAffaire(int $NumAffaire): self
    {
        $this->NumAffaire = $NumAffaire;

        return $this;
    }





}
