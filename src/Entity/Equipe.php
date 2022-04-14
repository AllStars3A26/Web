<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank(message="Veuillez ajouter nom")
     */
    private $nom_equipe;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Choice({"football", "basketball" })
     * @Assert\NotBlank(message="Veuillez ajouter type")
     */
    private $type_equipe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez ajouter description")
     */
    private $description_equipe;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez ajouter mail")
     */
    private $mail_equipe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Veuillez ajouter nombre de joueur")
     */
    private $nbre_joueur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(?string $nom_equipe): self
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getTypeEquipe(): ?string
    {
        return $this->type_equipe;
    }

    public function setTypeEquipe(?string $type_equipe): self
    {
        $this->type_equipe = $type_equipe;

        return $this;
    }

    public function getDescriptionEquipe(): ?string
    {
        return $this->description_equipe;
    }

    public function setDescriptionEquipe(?string $description_equipe): self
    {
        $this->description_equipe = $description_equipe;

        return $this;
    }

    public function getMailEquipe(): ?string
    {
        return $this->mail_equipe;
    }

    public function setMailEquipe(?string $mail_equipe): self
    {
        $this->mail_equipe = $mail_equipe;

        return $this;
    }

    public function getNbreJoueur(): ?int
    {
        return $this->nbre_joueur;
    }

    public function setNbreJoueur(?int $nbre_joueur): self
    {
        $this->nbre_joueur = $nbre_joueur;

        return $this;
    }
}
