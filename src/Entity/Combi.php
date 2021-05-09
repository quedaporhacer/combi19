<?php

namespace App\Entity;

use App\Repository\CombiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CombiRepository::class)
 */
class Combi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $patente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modelo;

    /**
     * @Assert\Positive(message="El valor ingresado no es valido")
     * @ORM\Column(type="integer")
     */
    private $capacidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $calidad;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Chofer", inversedBy="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chofer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatente(): ?string
    {
        return $this->patente;
    }

    public function setPatente(string $patente): self
    {
        $this->patente = $patente;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }

    public function setCapacidad(int $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
    }

    public function getCalidad(): ?string
    {
        return $this->calidad;
    }

    public function setCalidad(string $calidad): self
    {
        $this->calidad = $calidad;

        return $this;
    }

    public function getChofer(): ?Chofer
    {
        return $this->chofer;
    }

    public function setChofer(Chofer $chofer): self
    {
        $this->chofer = $chofer;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->patente;
    }
}
