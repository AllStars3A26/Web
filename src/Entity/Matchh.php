<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Matchh
 *
 * @ORM\Table(name="matchh", indexes={@ORM\Index(name="t_m", columns={"id_tournoi"})})
 * @ORM\Entity
 */
class Matchh
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_match", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatch;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_equipe1", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Le Champ id equipe 1 est obligatoire")
     */
    private $idEquipe1;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_equipe2", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Le Champ id equipe2 est obligatoire")
     */
    private $idEquipe2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_match", type="date", nullable=false)
     */
    private $dateMatch;

    /**
     * @var int
     *
     * @ORM\Column(name="resultat_match", type="integer", nullable=false)
     * @Assert\NotBlank(message="Le Champ resultat est obligatoire")
     */
    private $resultatMatch;

    /**
     * @var \Tournoi
     *
     * @ORM\ManyToOne(targetEntity="Tournoi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tournoi", referencedColumnName="id_tournoi")
     * })
     * @Assert\NotBlank(message="Le Champ idTournoi est obligatoire")
     */
    private $idTournoi;

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function getIdEquipe1(): ?Equipe
    {
        return $this->idEquipe1;
    }

    public function setIdEquipe1(?Equipe $idEquipe1): self
    {
        $this->idEquipe1 = $idEquipe1;

        return $this;
    }

    public function getIdEquipe2(): ?Equipe
    {
        return $this->idEquipe2;
    }

    public function setIdEquipe2(?Equipe $idEquipe2): self
    {
        $this->idEquipe2 = $idEquipe2;

        return $this;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(?\DateTimeInterface $dateMatch): self
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    public function getResultatMatch(): ?int
    {
        return $this->resultatMatch;
    }

    public function setResultatMatch(int $resultatMatch): self
    {
        $this->resultatMatch = $resultatMatch;

        return $this;
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


}
