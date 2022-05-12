<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParticipantsTournoi
 *
 * @ORM\Table(name="participants_tournoi", indexes={@ORM\Index(name="fk_tournoi", columns={"id_tournoi"}), @ORM\Index(name="fk_equipe", columns={"id_equipe"})})
 * @ORM\Entity
 */
class ParticipantsTournoi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_participant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idParticipant;

    /**
     * @var \Tournoi
     *
     * @ORM\ManyToOne(targetEntity="Tournoi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tournoi", referencedColumnName="id_tournoi")
     * })
     */
    private $idTournoi;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_equipe", referencedColumnName="id")
     * })
     */
    private $idEquipe;

    public function getIdParticipant(): ?int
    {
        return $this->idParticipant;
    }

    public function getIdTournoi(): ?Tournoi
    {
        return $this->idTournoi;
    }

    public function setIdTournoi(?Tournoi $idTournoi): self
    {
        $this->idTournoi = $idTournoi;

        return $this;
    }

    public function getIdEquipe(): ?Equipe
    {
        return $this->idEquipe;
    }

    public function setIdEquipe(?Equipe $idEquipe): self
    {
        $this->idEquipe = $idEquipe;

        return $this;
    }


}
