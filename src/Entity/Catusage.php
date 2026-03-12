<?php
namespace App\Entity;
use App\Repository\CatusageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatusageRepository::class)]
class Catusage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomCatusage = null;

    #[ORM\OneToMany(targetEntity: Typeusage::class, mappedBy: 'catusage')]
    private Collection $typeusages;

    public function __construct()
    {
        $this->typeusages = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNomCatusage(): ?string { return $this->nomCatusage; }
    public function setNomCatusage(string $nomCatusage): static { $this->nomCatusage = $nomCatusage; return $this; }
    public function getTypeusages(): Collection { return $this->typeusages; }
    public function __toString(): string { return $this->nomCatusage ?? ''; }
}
