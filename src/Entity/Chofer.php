<?php

namespace App\Entity;

use App\Repository\ChoferRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ChoferRepository::class)
 * 
 */
class Chofer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity=Combi::class, mappedBy="chofer",cascade={"persist"})
     * 
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contacto;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viajando;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContacto(): ?string
    {
        return $this->contacto;
    }

    public function setContacto(string $contacto): self
    {
        $this->contacto = $contacto;

        return $this;
    }

    public function __toString(): ?string 
    {
        return $this->user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getViajando(): ?bool
    {
        return $this->viajando;
    }

    public function setViajando(bool $viajando): self
    {
        $this->viajando = $viajando;

        return $this;
    } 

    public function iniciarViaje(): self
    {
        $this->viajando = true;
        return $this;
    }
    
    public function finalizarViaje(): self
    {
        $this->viajando = false;
        return $this;
    } 
}
