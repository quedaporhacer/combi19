<?php

namespace App\Entity;

use App\Repository\ConsumoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumoRepository::class)
 */
class Consumo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Insumo::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $insumo;

    /**
     * @ORM\OneToOne(targetEntity=Ticket::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Ticket;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInsumo(): ?Insumo
    {
        return $this->insumo;
    }

    public function setInsumo(Insumo $insumo): self
    {
        $this->insumo = $insumo;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->Ticket;
    }

    public function setTicket(Ticket $Ticket): self
    {
        $this->Ticket = $Ticket;

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

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
