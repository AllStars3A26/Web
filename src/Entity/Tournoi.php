<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournoi
 *
 * @ORM\Table(name="tournoi")
 * @ORM\Entity
 */
class Tournoi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_tournoi", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTournoi;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_tournoi", type="string", length=50, nullable=false)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     */
    private $nomTournoi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_tournoi", type="date", nullable=false)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $dateTournoi;

    /**
     * @var int
     *
     * @ORM\Column(name="resultat_tournoi", type="integer", nullable=false)
     *@Assert\Regex(pattern="/^\(0\)[0-9]*$", message="number_only")
     */
    private $resultatTournoi;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_participants", type="integer", nullable=true)
     *@Assert\Regex(pattern="/^\(0\)[0-9]*$", message="number_only")
     */
    private $nbParticipants;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_tournoi", type="string", length=100, nullable=true)
     */
    private $imageTournoi;

    /**
     * @var int|null
     *
     * @ORM\Column(name="heure", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^\(0\)[0-9]*$", message="number_only")
     */
    private $heure;

    public function getIdTournoi(): ?int
    {
        return $this->idTournoi;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(string $nomTournoi): self
    {
        $this->nomTournoi = $nomTournoi;

        return $this;
    }

    public function getDateTournoi(): ?\DateTimeInterface
    {
        return $this->dateTournoi;
    }

    public function setDateTournoi(\DateTimeInterface $dateTournoi): self
    {
        $this->dateTournoi = $dateTournoi;

        return $this;
    }

    public function getResultatTournoi(): ?int
    {
        return $this->resultatTournoi;
    }

    public function setResultatTournoi(int $resultatTournoi): self
    {
        $this->resultatTournoi = $resultatTournoi;

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

    public function getImageTournoi(): ?string
    {
        return $this->imageTournoi;
    }

    public function setImageTournoi(?string $imageTournoi): self
    {
        $this->imageTournoi = $imageTournoi;

        return $this;
    }

    public function getHeure(): ?int
    {
        return $this->heure;
    }

    public function setHeure(?int $heure): self
    {
        $this->heure = $heure;

        return $this;
    }


}
