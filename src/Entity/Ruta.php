<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RutaRepository::class)
 */
class Ruta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="App\Entity\Viaje", mappedBy="ruta")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lugar::class, inversedBy="nombre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $origen;

    /**
     * @ORM\ManyToOne(targetEntity=Lugar::class, inversedBy="nombre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $destino;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Los kilomtros ingresados no son validos")
     */
    private $kilometros;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigen(): ?Lugar
    {
        return $this->origen;
    }

    public function setOrigen(Lugar $origen): self
    {
        $this->origen = $origen;

        return $this;
    }

    public function getDestino(): ?Lugar
    {
        return $this->destino;
    }

    public function setDestino(Lugar $destino): self
    {
        $this->destino = $destino;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function __toString (): ?string
    {
        return $this->origen." - ".$this->destino;
    }

    public function getKilometros(): ?int
    {
        return $this->kilometros;
    }

    public function setKilometros(int $kilometros): self
    {
        $this->kilometros = $kilometros;

        return $this;
    }
}
