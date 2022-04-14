<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Calidation\Constraints as Assert;
/**
 * ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
  * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ticket", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id_ticket;

    /**
     * @var int
     *@Assert\NotBlank(message="Veuillez ajouter id")
     * @ORM\Column(name="prix", type="int", nullable=false)
     */
    private $prix_ticket;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=false)
     */
    private $libelle_ticket;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type_ticket;

    /**
     *  @var \DateTime
     *
     * @ORM\Column(name="date", type="date", length=20, nullable=false)
     */
    private $date_ticket;

    

    public function getId_ticket(): ?int
    {
        return $this->id_ticket;
    }

    public function getPrix_ticket(): ?int
    {
        return $this->prix_ticket;
    }

    public function setPrix_ticket(int $prix_ticket): self
    {
        $this->prix_ticket = $prix_ticket;

        return $this;
    }

    public function getLibelle_ticket(): ?string
    {
        return $this->libelle_ticket;
    }

    public function setLibelle_ticket(string $libelle_ticket): self
    {
        $this->libelle_ticket = $libelle_ticket;

        return $this;
    }

    public function getType_ticket(): ?string
    {
        return $this->type_ticket;
    }

    public function setType_ticket(string $type_ticket): self
    {
        $this->type_ticket = $type_ticket;

        return $this;
    }

    public function getDate_ticket(): ?int
    {
        return $this->date_ticket;
    }

    public function setDate_ticket(int $date_ticket): self
    {
        $this->date_ticket = $date_ticket;

        return $this;
    }


}
