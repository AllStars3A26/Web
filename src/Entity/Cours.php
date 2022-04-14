<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity
  * @ORM\Entity(repositoryClass="App\Repository\CoursRepository")
 */
class Cours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cours", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCours;

    /**
     * @var string
     *@Assert\NotBlank(message="Le titre est obligatoire")
     *@Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le titre doit etre composé de lettres seulement"
     * )
     * @ORM\Column(name="titre", type="string", length=20, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *@Assert\NotBlank(message="Le nom de l'entraineur est obligatoire")
     *@Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="LLe nom de l'entraineur doit etre composé de lettres seulement"
     * )
     * @ORM\Column(name="nome", type="string", length=20, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *@Assert\NotBlank(message="Le type est obligatoire")
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var string
     *@Assert\NotBlank(message="La valeur est obligatoire")
     * @ORM\Column(name="imc", type="string", length=20, nullable=false)
     */
    private $imc;

    /**
     * @var int
     *@Assert\NotBlank(message="Le nombre des heures est obligatoire")
     * @Assert\GreaterThanOrEqual(1,message="le nombre d'heure doit être supérieur ou égal à 1")
     * *@Assert\LessThanOrEqual(
     *     value = 80,
     * message="le nombre d'heure doit être inférieur ou égal à 80"
     * )
     * @ORM\Column(name="nb_heure", type="integer", nullable=false)
     */
    private $nbHeure;

    public function getIdCours(): ?int
    {
        return $this->idCours;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImc(): ?string
    {
        return $this->imc;
    }

    public function setImc(string $imc): self
    {
        $this->imc = $imc;

        return $this;
    }

    public function getNbHeure(): ?int
    {
        return $this->nbHeure;
    }

    public function setNbHeure(int $nbHeure): self
    {
        $this->nbHeure = $nbHeure;

        return $this;
    }


}
