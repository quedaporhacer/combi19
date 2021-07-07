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
     * @ORM\ManyToOne(targetEntity=Viaje::class, inversedBy="imprevistos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $viaje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    public function __construct()
    {
        $this->state = false;
    }

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

    public function getViaje(): ?Viaje
    {
        return $this->viaje;
    }

    public function setViaje(?Viaje $viaje): self
    {
        $this->viaje = $viaje;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function resolver(): self
    {
        $this->state = true;
        return $this;
    }    


}
