<?php
namespace App\Entity;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomCategorie = null;

    #[ORM\OneToMany(targetEntity: Optionnel::class, mappedBy: 'categorie')]
    private Collection $optionnels;

    public function __construct()
    {
        $this->optionnels = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomCategorie(): ?string { return $this->nomCategorie; }
    public function setNomCategorie(string $nomCategorie): static { $this->nomCategorie = $nomCategorie; return $this; }
    public function getOptionnels(): Collection { return $this->optionnels; }
    public function __toString(): string { return $this->nomCategorie ?? ''; }
}
