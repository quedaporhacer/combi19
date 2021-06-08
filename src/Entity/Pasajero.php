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
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="App\Entity\User")
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="pasajero", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThan("-18 years", message="Debe ser mayor de edad para poder registrarse")
     */
    private $nacimiento;

    /**
     * @ORM\OneToOne(targetEntity=Tarjeta::class, mappedBy="propietario", cascade={"persist", "remove"})
     */
    private $tarjeta;

    public function __construct()
    {
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
        return $this->tarjeta != null;
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

    public function getTarjeta(): ?Tarjeta
    {
        return $this->tarjeta;
    }

    public function setTarjeta(Tarjeta $tarjeta): self
    {
        // set the owning side of the relation if necessary
        if ($tarjeta->getPropietario() !== $this) {
            $tarjeta->setPropietario($this);
        }

        $this->tarjeta = $tarjeta;

        return $this;
    }
}
