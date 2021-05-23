<?php

namespace App\Entity;

use App\Repository\ViajeRepository;
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
     * @ORM\Column(type="datetime")
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
        return $this->ruta." combi: ".$this->combi;
    }
    
    public function finished(): ?bool   
    {
        return  $this->salida < new \DateTime("now")  &&  $this->llegada > new \DateTime("now");
    }

    public function disponible(): ?bool
    {
        return true;
    }

    public function enCurso(): ?bool
    {
        return false;
    }

    public function inicio(){
        $this->salida < new \DateTime("now") ;
    }


}
