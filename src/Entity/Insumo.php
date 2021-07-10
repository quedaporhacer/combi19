<?php

namespace App\Entity;

use App\Repository\InsumoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InsumoRepository::class)
 * @UniqueEntity("nombre",message="El insumo ya esta registrado")
 */
class Insumo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    /**
     * @Assert\Positive(message="El valor ingresado no es valido")
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @Assert\Positive(message="El valor ingresado no es valido")
     * @ORM\Column(type="integer")
     */
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->nombre."-".$this->precio."$" ;
    }

}
