<?php
namespace App\Entity;
use App\Repository\MoeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoeRepository::class)]
class Moe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomMoe = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomMoe = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telMoe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mailMoe = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    private ?Entreprise $entreprise = null;

    public function getId(): ?int { return $this->id; }
    public function getNomMoe(): ?string { return $this->nomMoe; }
    public function setNomMoe(string $nomMoe): static { $this->nomMoe = $nomMoe; return $this; }
    public function getPrenomMoe(): ?string { return $this->prenomMoe; }
    public function setPrenomMoe(string $prenomMoe): static { $this->prenomMoe = $prenomMoe; return $this; }
    public function getTelMoe(): ?string { return $this->telMoe; }
    public function setTelMoe(?string $telMoe): static { $this->telMoe = $telMoe; return $this; }
    public function getMailMoe(): ?string { return $this->mailMoe; }
    public function setMailMoe(?string $mailMoe): static { $this->mailMoe = $mailMoe; return $this; }
    public function getEntreprise(): ?Entreprise { return $this->entreprise; }
    public function setEntreprise(?Entreprise $entreprise): static { $this->entreprise = $entreprise; return $this; }
    public function getFullName(): string { return $this->prenomMoe . ' ' . $this->nomMoe; }
    public function __toString(): string { return $this->getFullName(); }
}
