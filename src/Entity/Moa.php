<?php
namespace App\Entity;
use App\Repository\MoaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoaRepository::class)]
class Moa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomMoa = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomMoa = null;

    #[ORM\Column(length: 20)]
    private ?string $telMoa = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mailMoa = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    private ?Entreprise $entreprise = null;

    public function getId(): ?int { return $this->id; }
    public function getNomMoa(): ?string { return $this->nomMoa; }
    public function setNomMoa(string $nomMoa): static { $this->nomMoa = $nomMoa; return $this; }
    public function getPrenomMoa(): ?string { return $this->prenomMoa; }
    public function setPrenomMoa(string $prenomMoa): static { $this->prenomMoa = $prenomMoa; return $this; }
    public function getTelMoa(): ?string { return $this->telMoa; }
    public function setTelMoa(string $telMoa): static { $this->telMoa = $telMoa; return $this; }
    public function getMailMoa(): ?string { return $this->mailMoa; }
    public function setMailMoa(?string $mailMoa): static { $this->mailMoa = $mailMoa; return $this; }
    public function getEntreprise(): ?Entreprise { return $this->entreprise; }
    public function setEntreprise(?Entreprise $entreprise): static { $this->entreprise = $entreprise; return $this; }
    public function getFullName(): string { return $this->prenomMoa . ' ' . $this->nomMoa; }
    public function __toString(): string { return $this->getFullName(); }
}
