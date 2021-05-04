<?php

namespace App\Entity;

use App\Repository\ImprevistoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImprevistoRepository::class)
 */
class Imprevisto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $imprevisto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hora;

    /**
     * @ORM\OneToOne(targetEntity=Viaje::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $viaje;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImprevisto(): ?string
    {
        return $this->imprevisto;
    }

    public function setImprevisto(string $imprevisto): self
    {
        $this->imprevisto = $imprevisto;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
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
}
