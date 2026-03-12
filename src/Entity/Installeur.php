<?php

namespace App\Entity;

use App\Repository\InstalleurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=InstalleurRepository::class)
 * @UniqueEntity(
 * fields={"nom_installeur"},
 * message="Cet installeur existe déjà")
 */
class Installeur
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
    private $nom_installeur;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mail;


    public function __toString()
    {
        return $this->nom_installeur;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomInstalleur(): ?string
    {
        return $this->nom_installeur;
    }

    public function setNomInstalleur(string $nom_installeur): self
    {
        $this->nom_installeur = $nom_installeur;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    
}
