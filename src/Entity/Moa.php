<?php

namespace App\Entity;

use App\Repository\MoaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoaRepository::class)
 */
class Moa
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
    private $nom_moa;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom_moa;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tel_moa;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail_moa;

    
    public function __toString()
    {
        return $this->nom_moa;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMoa(): ?string
    {
        return $this->nom_moa;
    }

    public function setNomMoa(string $nom_moa): self
    {
        $this->nom_moa = $nom_moa;

        return $this;
    }

    public function getPrenomMoa(): ?string
    {
        return $this->prenom_moa;
    }

    public function setPrenomMoa(string $prenom_moa): self
    {
        $this->prenom_moa = $prenom_moa;

        return $this;
    }

    public function getTelMoa(): ?string
    {
        return $this->tel_moa;
    }

    public function setTelMoa(string $tel_moa): self
    {
        $this->tel_moa = $tel_moa;

        return $this;
    }

    public function getMailMoa(): ?string
    {
        return $this->mail_moa;
    }

    public function setMailMoa(?string $mail_moa): self
    {
        $this->mail_moa = $mail_moa;

        return $this;
    }


   
}
