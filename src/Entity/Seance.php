<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 *
 * @ORM\Table(name="seance", indexes={@ORM\Index(name="fk", columns={"cours_cp"})})
 * @ORM\Entity
 
 */
class Seance

{
    /**
     * @var int
     *
     * @ORM\Column(name="id_seance", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSeance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_seance", type="date", nullable=false)
     */
    private $dateSeance;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_seance", type="string", length=20, nullable=false)
     */
    private $heureSeance;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_T", type="string", length=20, nullable=false)
     */
    private $nomT;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_E", type="string", length=20, nullable=false)
     */
    private $nomE;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_participants", type="integer", nullable=true)
     */
    private $nbParticipants;

    /**
     * @var int
     *
     * @ORM\Column(name="cours_cp",type="integer",nullable=false)
     */
    private $coursCp;
  

    public function getIdSeance(): ?int
    {
        return $this->idSeance;
    }

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;

        return $this;
    }

    public function getHeureSeance(): ?string
    {
        return $this->heureSeance;
    }

    public function setHeureSeance(string $heureSeance): self
    {
        $this->heureSeance = $heureSeance;

        return $this;
    }

    public function getNomT(): ?string
    {
        return $this->nomT;
    }

    public function setNomT(string $nomT): self
    {
        $this->nomT = $nomT;

        return $this;
    }

    public function getNomE(): ?string
    {
        return $this->nomE;
    }

    public function setNomE(string $nomE): self
    {
        $this->nomE = $nomE;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(?int $nbParticipants): self
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

    public function getCoursCp(): ?int
    {
        return $this->coursCp;
    }

    public function setCoursCp(?int $coursCp): self
    {
        $this->coursCp = $coursCp;

        return $this;
    }
  

}
