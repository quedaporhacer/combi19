<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $testeo;

    /**
     * @ORM\ManyToOne(targetEntity=Pasajero::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pasajero;

    /**
     * @ORM\ManyToOne(targetEntity=Viaje::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $viaje;

    /**
     * @ORM\OneToOne(targetEntity=Comentario::class, inversedBy="ticket", cascade={"persist", "remove"})
     */
    private $comentario;

    /**
     * @ORM\Column(type="bigint",nullable=true)
     * @Assert\Length(
     *      min = 16,
     *      max = 16,
     *      minMessage = "el numero ingresado no es valido",
     *      maxMessage = "el numero ingresado no es valido",
     *      exactMessage = "el numero ingresado debe ser de 16 caracteres")
     */
    private $numero;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      minMessage = "el numero ingresado no es valido",
     *      maxMessage = "el numero ingresado no es valido",
     *      exactMessage = "el numero ingresado debe ser de 3 caracteres")
     */
    private $codigo;

    /**
     * @ORM\Column(type="date",nullable=true)
     * @Assert\GreaterThan("today", message="la tarjeta esta vencida")
     */
    private $vencimiento;

    /**
     * @ORM\OneToMany(targetEntity=Consumo::class, mappedBy="ticket", orphanRemoval=true)
     */
    private $consumos;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\OneToMany(targetEntity=Tercero::class, mappedBy="ticket", orphanRemoval=true)
     */
    private $terceros;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reembolso;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    private $descripcionReembolso;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cobro;

    public function __construct()
    {
        $this->consumos = new ArrayCollection();
        $this->terceros = new ArrayCollection();
        $this->cobro = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getViaje(): ?Viaje
    {
        return $this->viaje;
    }

    public function setViaje(?Viaje $viaje): self
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getVencimiento(): ?\DateTimeInterface
    {
        return $this->vencimiento;
    }

    public function setVencimiento(\DateTimeInterface $vencimiento): self
    {
        $this->vencimiento = $vencimiento;

        return $this;
    }

    public function __toString(): ?string 
    {
        return $this->id;
    }

    /**
     * @return Collection|Consumo[]
     */
    public function getConsumos(): Collection
    {
        return $this->consumos;
    }

    public function addConsumo(Consumo $consumo): self
    {
        if (!$this->consumos->contains($consumo)) {
            $this->consumos[] = $consumo;
            $consumo->setTicket($this);
        }

        return $this;
    }

    public function removeConsumo(Consumo $consumo): self
    {
        if ($this->consumos->removeElement($consumo)) {
            // set the owning side to null (unless already changed)
            if ($consumo->getTicket() === $this) {
                $consumo->setTicket(null);
            }
        }

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function getPrecioConsumo(): ?float
    {
        $val=0;
        foreach($this->consumos as $consumo){
            $val += $consumo->getPrecio(); 
        }
        return  $val;
    }

    public function getPrecioTerceros()
    {
        $val=0;
        foreach($this->terceros as $tercero){
            $val = $val + $tercero->getPrecio();
        }
        return $val;
    }

    public function getPrecioTotal(): ?float
    {
        return $this->getPrecio() 
            + $this->getPrecioConsumo() 
            + $this->getPrecioTerceros();
    }

    public function setPrecio(float $precio): self
    {
        if($this->pasajero->getMembresia())
            $this->precio= ($precio * 0.9);
        else
            $this->precio = $precio;
        
        return $this;
    }

    /**
     * @return Collection|Tercero[]
     */
    public function getTerceros(): Collection
    {
        return $this->terceros;
    }

    public function addTercero(Tercero $tercero): self
    {
        if (!$this->terceros->contains($tercero)) {
            $this->terceros[] = $tercero;
            $tercero->setTicket($this);
        }

        return $this;
    }

    public function removeTercero(Tercero $tercero): self
    {
        if ($this->terceros->removeElement($tercero)) {
            // set the owning side to null (unless already changed)
            if ($tercero->getTicket() === $this) {
                $tercero->setTicket(null);
            }
        }

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

    public function getCobro(): ?bool
    {
        return $this->cobro;
    }

    public function setCobro(bool $cobro): self
    {
        $this->cobro = $cobro;

        return $this;
    }

    public function setTarjeta(Tarjeta $tarjeta): self
    {
        $this->numero = $tarjeta->getNumero();
        $this->codigo = $tarjeta->getCodigo();
        $this->vencimiento = $tarjeta->getVencimiento();

        return $this;
    }
}
