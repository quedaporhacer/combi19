<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Viaje::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $viaje;

    /**
     * @ORM\OneToOne(targetEntity=Comentario::class, cascade={"persist", "remove"})
     */
    private $comentario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $testeo;

    /**
     * @ORM\ManyToOne(targetEntity=Pasajero::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pasajero;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getViaje(): ?Viaje
    {
        return $this->viaje;
    }

    public function setViaje(Viaje $viaje): self
    {
        $this->viaje = $viaje;

        return $this;
    }

    public function getComentario(): ?Comentario
    {
        return $this->comentario;
    }

    public function setComentario(?Comentario $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getTesteo(): ?bool
    {
        return $this->testeo;
    }

    public function setTesteo(?bool $testeo): self
    {
        $this->testeo = $testeo;

        return $this;
    }

    public function getPasajero(): ?Pasajero
    {
        return $this->pasajero;
    }

    public function setPasajero(?Pasajero $pasajero): self
    {
        $this->pasajero = $pasajero;

        return $this;
    }
}
