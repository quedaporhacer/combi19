<?php

namespace App\Entity;

use App\Repository\CalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalidadRepository::class)
 */
class Calidad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Combi::class, mappedBy="calidad", orphanRemoval=true)
     */
    private $combis;

    public function __construct()
    {
        $this->combis = new ArrayCollection();
    }

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

    /**
     * @return Collection|Combi[]
     */
    public function getCombis(): Collection
    {
        return $this->combis;
    }

    public function addCombi(Combi $combi): self
    {
        if (!$this->combis->contains($combi)) {
            $this->combis[] = $combi;
            $combi->setCalidad($this);
        }

        return $this;
    }

    public function removeCombi(Combi $combi): self
    {
        if ($this->combis->removeElement($combi)) {
            // set the owning side to null (unless already changed)
            if ($combi->getCalidad() === $this) {
                $combi->setCalidad(null);
            }
        }

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->nombre;
    }
}
