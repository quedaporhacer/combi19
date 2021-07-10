<?php

namespace App\Entity;

use App\Repository\TerceroRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TerceroRepository::class)
 */
class Tercero
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
    private $dni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $testeo;

    /**
     * @ORM\ManyToOne(targetEntity=Ticket::class, inversedBy="terceros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reembolso;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    private $descripcionReembolso;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?int
    {
        return $this->dni;
    }

    public function setDni(int $dni): self
    {
        $this->dni = $dni;

        return $this;
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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

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

    public function getTesteo(): ?bool
    {
        return $this->testeo;
    }

    public function setTesteo(?bool $testeo): self
    {
        $this->testeo = $testeo;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getReembolso(): ?bool
    {
        return $this->reembolso;
    }

    public function setReembolso(?bool $reembolso): self
    {
        $this->reembolso = $reembolso;

        return $this;
    }

    public function getDescripcionReembolso(): ?string
    {
        return $this->descripcionReembolso;
    }

    public function setDescripcionReembolso(?string $descripcionReembolso): self
    {
        $this->descripcionReembolso = $descripcionReembolso;

        return $this;
    }
}
