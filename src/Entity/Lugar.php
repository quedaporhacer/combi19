<?php

namespace App\Entity;

use App\Repository\LugarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LugarRepository::class)
 */
class Lugar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\OneToMany(targetEntity="App\Entity\Ruta", mappedBy="origen")
     * @ORM\OneToMany(targetEntity="App\Entity\Ruta", mappedBy="destino")
     */
    private $nombre;

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
    
    public function __toString(): string
    {
        return $this->nombre;
    }
}
