<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @Assert\Length(
     *      min = 3,
     *      minMessage=" Entrer un nom au mini de 3 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     *
     */
    private $nom;

    /**
     * @Assert\NotBlank(message=" prenom doit etre non vide")
     * @Assert\Length(
     *      min = 4,
     *      minMessage=" Entrer un titre au mini de 4 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="Adresse  doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=300)
     */
    private $adresse;

    /**
     * @Assert\NotBlank(message="Adresse  doit etre non vide")
     * @ORM\Column(type="string", length=300, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Adresse  doit etre non vide")
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $role;



    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $date_inscrit_u;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $desc_u;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type_e;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_contract_e;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dure_contract_e;


    /**
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "doit etre =8 ",
     *      maxMessage = "doit etre =8" )
     * @ORM\Column(type="string", length=255)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getDateInscritU(): ?\DateTimeInterface
    {
        return $this->date_inscrit_u;
    }

    public function setDateInscritU(\DateTimeInterface $date_inscrit_u): self
    {
        $this->date_inscrit_u = $date_inscrit_u;

        return $this;
    }

    public function getDescU(): ?string
    {
        return $this->desc_u;
    }

    public function setDescU(?string $desc_u): self
    {
        $this->desc_u = $desc_u;

        return $this;
    }

    public function getTypeE(): ?string
    {
        return $this->type_e;
    }

    public function setTypeE(?string $type_e): self
    {
        $this->type_e = $type_e;

        return $this;
    }

    public function getDateContractE(): ?\DateTimeInterface
    {
        return $this->date_contract_e;
    }

    public function setDateContractE(?\DateTimeInterface $date_contract_e): self
    {
        $this->date_contract_e = $date_contract_e;

        return $this;
    }

    public function getDureContractE(): ?int
    {
        return $this->dure_contract_e;
    }

    public function setDureContractE(?int $dure_contract_e): self
    {
        $this->dure_contract_e = $dure_contract_e;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    public function eraseCredentials()
    {
    }
    public function getRoles()
    {
        return $this->roles;
    }
}
