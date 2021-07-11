<?php

namespace App\Entity;

use App\Repository\ViajeRepository;
use ArrayAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ViajeRepository::class)
 */
class Viaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today",message="la fecha ingresada no es valida")
     */
    private $salida;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Assert\GreaterThan("today",message="la fecha ingresada no es valida")
     */
    private $llegada;

    /**
     * @ORM\ManyToOne(targetEntity=Combi::class,inversedBy="patente", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $combi;

    /**
     * @ORM\ManyToOne(targetEntity=Ruta::class,inversedBy="id", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ruta;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="viaje", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=Imprevisto::class, mappedBy="viaje", orphanRemoval=true)
     */
    private $imprevistos;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->imprevistos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalida(): ?\DateTimeInterface
    {
        return $this->salida;
    }

    public function setSalida(\DateTimeInterface $salida): self
    {
        $this->salida = $salida;

        return $this;
    }

    public function getLlegada(): ?\DateTimeInterface
    {
        return $this->llegada;
    }

    public function setLlegada(\DateTimeInterface $llegada): self
    {
        $this->llegada = $llegada;

        return $this;
    }

    public function getCombi(): ?Combi
    {
        return $this->combi;
    }

    public function setCombi(Combi $combi): self
    {
        $this->combi = $combi;

        return $this;
    }

    public function getRuta(): ?Ruta
    {
        return $this->ruta;
    }

    public function setRuta(Ruta $ruta): self
    {
        $this->ruta = $ruta;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->ruta.", Combi ".$this->combi;
    }
    
    public function finished(): ?bool   
    {
        return  $this->salida < new \DateTime("now")  &&  $this->llegada > new \DateTime("now");
    }

    public function disponible(\DateTime $salida): ?bool
    {
        return $salida <> $this->getSalida();
        
        /*return (($this->getSalida()>$salida && $this->getSalida()<$llegada) ||
        ($this->getLlegada()>$salida && $this->getLlegada()<$llegada) || 
        ($this->getSalida()<$salida && $this->getLlegada()<$llegada));*/
    }

    public function enCurso(): ?bool
    {
        return false;
    }

    public function inicio(): ?bool
    {
        return $this->estado == "En curso";
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
            $ticket->setViaje($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getViaje() === $this) {
                $ticket->setViaje(null);
            }
        }

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return Collection|Imprevisto[]
     */
    public function getImprevistos(): Collection
    {
        return $this->imprevistos;
    }

    public function addImprevisto(Imprevisto $imprevisto): self
    {
        if (!$this->imprevistos->contains($imprevisto)) {
            $this->imprevistos[] = $imprevisto;
            $imprevisto->setViaje($this);
        }

        return $this;
    }

    public function removeImprevisto(Imprevisto $imprevisto): self
    {
        if ($this->imprevistos->removeElement($imprevisto)) {
            // set the owning side to null (unless already changed)
            if ($imprevisto->getViaje() === $this) {
                $imprevisto->setViaje(null);
            }
        }

        return $this;
    }

    public function iniciar(): self 
    {
        $this->estado = "En curso";
        return $this;
    }

    public function finalizar(\DateTime $llegada): self 
    {
        $this->estado = "Finalizado";
        $this->llegada =  $llegada;
        return $this;
    }

    public function getPasajeros(): ?ArrayCollection
    {   
        return $this->tickets->map(function($pasajero) {
            return $pasajero->getPasajero();
        });
    }

    public function getTicketOfPasajero(Pasajero $pasajero): Ticket
    {   
        $arr=$this->getTickets();
        foreach($arr as $ticket)
        {   
            if($ticket->getPasajero() == $pasajero){
                return $ticket;
            }
        }
        return false;
    }

    public function isUnique(int $dni): bool
    {   
        $arr=$this->getTickets();
        foreach($arr as $ticket)
        {   
            if($ticket->getPasajero()->getDni() == $dni){
                return false;
            }
            else{
                $arr2=$ticket->getTerceros();
                foreach($arr2 as $tercero)
                {
                    if($tercero->getDni() == $dni){
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function asientosOcupados(): int
    {   
        $cont=0;
        $tickets=$this->tickets;
        $cont=$cont+count($tickets);
        foreach($tickets as $ticket){
            $arr=$ticket->getTerceros();
            $cont=$cont+count($arr);
        }
        return $cont;
    }

    public function asientosDisponibles(): int
    {   
        return $this->asientosOcupados()-$this->combi->getCapacidad();
    }

    public function lleno(): int
    {   
        return $this->asientosDisponibles() == 0;
    }

    public function pasajeroCompro(Pasajero $pasajero): bool
    {

        foreach ($this->getTickets() as $ticket){

            if($pasajero->getId() == $ticket->getPasajero()->getId()){
                return true;
            }
             
        }
        return false; 
    }


}
