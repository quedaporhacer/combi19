<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComentarioRepository::class)
 */
class Comentario
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
    private $comentario;

    /**
     * @ORM\OneToOne(targetEntity=Ticket::class, mappedBy="comentario", cascade={"persist", "remove"})
     */
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        // unset the owning side of the relation if necessary
        if ($ticket === null && $this->ticket !== null) {
            $this->ticket->setComentario(null);
        }

        // set the owning side of the relation if necessary
        if ($ticket !== null && $ticket->getComentario() !== $this) {
            $ticket->setComentario($this);
        }

        $this->ticket = $ticket;

        return $this;
    }
}
