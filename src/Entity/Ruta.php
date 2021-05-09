<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RutaRepository::class)
 */
class Ruta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lugar::class, inversedBy="nombre", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $origen;

    /**
     * @ORM\ManyToOne(targetEntity=Lugar::class, inversedBy="nombre", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $destino;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $descripcion;

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
}
