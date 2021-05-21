<?php

namespace App\Entity;

use App\Repository\TarjetaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=TarjetaRepository::class)
 * @UniqueEntity("numero",message="Este numero ya esta siendo utilizado")
 */
class Tarjeta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint",unique=true, length=16)
     * 
     * @Assert\Length(
     *      min = 16,
     *      max = 16,
     *      minMessage = "el numero ingresado no es valido",
     *      maxMessage = "el numero ingresado no es valido",
     *      exactMessage = "el numero ingresado debe ser de 16 caracteres"
     * 
     * )\Numero
     * 
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      minMessage = "el numero ingresado no es valido",
     *      maxMessage = "el numero ingresado no es valido",
     *      exactMessage = "el numero ingresado debe ser de 3 caracteres")
     */
    private $codigo;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today", message="la tarjeta esta vencida")
     */
    private $vencimiento;

    /**
     * @ORM\ManyToOne(targetEntity=Pasajero::class, inversedBy="tarjetas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propietario;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
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

    public function getPropietario(): ?Pasajero
    {
        return $this->propietario;
    }

    public function setPropietario(?Pasajero $propietario): self
    {
        $this->propietario = $propietario;

        return $this;
    }

}
