<?php

namespace App\Entity;

use App\Repository\ViajeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ViajeRepository::class)
 */
class Viaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $salida;

    /**
     * @ORM\Column(type="datetime")
     */
    private $llegada;

    /**
     * @ORM\OneToOne(targetEntity=Combi::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $combi;

    /**
     * @ORM\OneToOne(targetEntity=Ruta::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalida(): ?\DateTimeInterface
    {
        return $this->salida;
    }

    public function setSalida(\DateTimeInterface $salida): self
    {
        $this->salida = $salida;

        return $this;
    }

    public function getLlegada(): ?\DateTimeInterface
    {
        return $this->llegada;
    }

    public function setLlegada(\DateTimeInterface $llegada): self
    {
        $this->llegada = $llegada;

        return $this;
    }

    public function getCombi(): ?Combi
    {
        return $this->combi;
    }

    public function setCombi(Combi $combi): self
    {
        $this->combi = $combi;

        return $this;
    }

    public function getRuta(): ?Ruta
    {
        return $this->ruta;
    }

    public function setRuta(Ruta $ruta): self
    {
        $this->ruta = $ruta;

        return $this;
    }
}
