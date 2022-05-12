<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TerrainRepository::class)
 */
class Terrain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Veuillez ajouter nom")
     */
    private $nom_terrain;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Choice({"football", "basketball" })
     * @Assert\NotBlank(message="Veuillez ajouter type")
     */
    private $type_terrain;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez ajouter description")
     */
    private $description_terrain;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez ajouter adresse")
     */
    private $adresse_terrain;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Choice({0, 1 })
     * @Assert\NotBlank(message="Veuillez ajouter disponibilite")
     */
    private $disponibilite;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="terrains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_equipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTerrain(): ?string
    {
        return $this->nom_terrain;
    }

    public function setNomTerrain(string $nom_terrain): self
    {
        $this->nom_terrain = $nom_terrain;

        return $this;
    }

    public function getTypeTerrain(): ?string
    {
        return $this->type_terrain;
    }

    public function setTypeTerrain(string $type_terrain): self
    {
        $this->type_terrain = $type_terrain;

        return $this;
    }

    public function getDescriptionTerrain(): ?string
    {
        return $this->description_terrain;
    }

    public function setDescriptionTerrain(string $description_terrain): self
    {
        $this->description_terrain = $description_terrain;

        return $this;
    }

    public function getAdresseTerrain(): ?string
    {
        return $this->adresse_terrain;
    }

    public function setAdresseTerrain(string $adresse_terrain): self
    {
        $this->adresse_terrain = $adresse_terrain;

        return $this;
    }

    public function getDisponibilite(): ?int
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(int $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getIdEquipe(): ?Equipe
    {
        return $this->id_equipe;
    }

    public function setIdEquipe(?Equipe $id_equipe): self
    {
        $this->id_equipe = $id_equipe;

        return $this;
    }
}
