<?php

namespace App\Entity;

use App\Repository\MoeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoeRepository::class)
 */
class Moe
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
    private $nom_moe;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom_moe;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tel_moe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail_moe;


    public function __toString()
    {
        return $this->nom_moe;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMoe(): ?string
    {
        return $this->nom_moe;
    }

    public function setNomMoe(string $nom_moe): self
    {
        $this->nom_moe = $nom_moe;

        return $this;
    }

    public function getPrenomMoe(): ?string
    {
        return $this->prenom_moe;
    }

    public function setPrenomMoe(string $prenom_moe): self
    {
        $this->prenom_moe = $prenom_moe;

        return $this;
    }

    public function getTelMoe(): ?string
    {
        return $this->tel_moe;
    }

    public function setTelMoe(?string $tel_moe): self
    {
        $this->tel_moe = $tel_moe;

        return $this;
    }

    public function getMailMoe(): ?string
    {
        return $this->mail_moe;
    }

    public function setMailMoe(?string $mail_moe): self
    {
        $this->mail_moe = $mail_moe;

        return $this;
    }
}
