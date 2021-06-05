<?php

namespace App\Entity;

use App\Repository\PasajeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PasajeroRepository::class)
 * @UniqueEntity(fields="dni", message="Este dni ya se encuentra registrado")
 */
class Pasajero
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
    private $dni;

    /**
     * @ORM\Column(type="boolean")
     */
    private $membresia;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="App\Entity\User")
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Tarjeta::class, mappedBy="propietario", orphanRemoval=true)
     */
    private $tarjetas;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="pasajero", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThan("-18 years", message="Debe ser mayor de edad para poder registrarse")
     */
    private $nacimiento;

    public function __construct()
    {
        $this->tarjetas = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getMembresia(): ?bool
    {
        return $this->membresia;
    }

    public function setMembresia(bool $membresia): self
    {
        $this->membresia = $membresia;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Tarjeta[]
     */
    public function getTarjetas(): Collection
    {
        return $this->tarjetas;
    }

    public function addTarjeta(Tarjeta $tarjeta): self
    {
        if (!$this->tarjetas->contains($tarjeta)) {
            $this->tarjetas[] = $tarjeta;
            $tarjeta->setPropietario($this);
        }

        return $this;
    }

    public function removeTarjeta(Tarjeta $tarjeta): self
    {
        if ($this->tarjetas->removeElement($tarjeta)) {
            // set the owning side to null (unless already changed)
            if ($tarjeta->getPropietario() === $this) {
                $tarjeta->setPropietario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setPasajero($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getPasajero() === $this) {
                $ticket->setPasajero(null);
            }
        }

        return $this;
    }

    public function __toString(): ?string 
    {
        return $this->user;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }
}
