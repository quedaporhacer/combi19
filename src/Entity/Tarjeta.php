<?php

namespace App\Entity;

use App\Repository\TarjetaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TarjetaRepository::class)
 */
class Tarjeta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="date")
     */
    private $vencimiento;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $propietario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getVencimiento(): ?\DateTimeInterface
    {
        return $this->vencimiento;
    }

    public function setVencimiento(\DateTimeInterface $vencimiento): self
    {
        $this->vencimiento = $vencimiento;

        return $this;
    }

    public function getPropietario(): ?User
    {
        return $this->propietario;
    }

    public function setPropietario(User $propietario): self
    {
        $this->propietario = $propietario;

        return $this;
    }
}
