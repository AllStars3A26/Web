<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Calidation\Constraints as Assert;
/**
 * Seance
 *
 * @ORM\Table(name="Evenement", indexes={@ORM\Index(name="fk", columns={"cours_cp"})})
 * @ORM\Entity
 
 */
class Seance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_evenement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id_evenement;

    /**
     * @var \DateTime
     *@Assert\NotBlank(message="Veuillez ajouter id")
     * @ORM\Column(name="date_evenement", type="date", nullable=false)
     */
    private $date_evenement;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_evenement", type="string", length=20, nullable=false)
     */
    private $libelle_evenement;

    /**
     * @var string
     *
     * @ORM\Column(name="type_evenement", type="string", length=20, nullable=false)
     */
    private $type_evenement;

    
  

    public function getId_Evenement(): ?int
    {
        return $this->id_evenement;
    }

    public function getDate_Evenement(): ?\DateTimeInterface
    {
        return $this->date_evenement;
    }

    public function setDate_Evenement(\DateTimeInterface $date_evenement): self
    {
        $this->date_evenement = $date_evenement;

        return $this;
    }

    public function getLibelle_Evenement(): ?string
    {
        return $this->libelle_evenement;
    }

    public function setLibelle_Evenement(string $libelle_evenement): self
    {
        $this->libelle_evenement = $libelle_evenement;

        return $this;
    }

    public function getType_Evenement(): ?string
    {
        return $this->type_evenement;
    }

    public function setType_Evenement(string $type_evenement): self
    {
        $this->type_evenement = $type_evenement;

        return $this;
    }

    

}
