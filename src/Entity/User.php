<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user", indexes={@ORM\Index(columns={"psudo"}, flags={"fulltext"})})
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="psudo", message="Username already taken")
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @Assert\NotBlank(message=" full doit etre non vide")
     * @Assert\Length(
     *      min = 4,
     *      minMessage=" Entrer un titre au mini de 4 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullname;

    /**
     * @Assert\NotBlank(message="Adresse  doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $adresse;

    /**
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "doit etre =8 ",
     *      maxMessage = "doit etre =8" )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

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
    private $date_e;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dure_e;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @Assert\NotBlank(message="Adresse  doit etre non vide")
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $psudo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

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

    public function getDateInscritU(): ?\DateTimeInterface
    {
        return $this->date_inscrit_u;
    }

    public function setDateInscritU(?\DateTimeInterface $date_inscrit_u): self
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

    public function getDateE(): ?\DateTimeInterface
    {
        return $this->date_e;
    }

    public function setDateE(?\DateTimeInterface $date_e): self
    {
        $this->date_e = $date_e;

        return $this;
    }

    public function getDureE(): ?int
    {
        return $this->dure_e;
    }

    public function setDureE(?int $dure_e): self
    {
        $this->dure_e = $dure_e;

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

    public function getPsudo(): ?string
    {
        return $this->psudo;
    }

    public function setPsudo(string $psudo): self
    {
        $this->psudo = $psudo;

        return $this;
    }
}
