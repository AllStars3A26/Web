<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idprod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomprod;

    /**
     * @ORM\Column(type="float")
     */
    private $prixprod;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true)
     */
    private $imgprod;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantprod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descprod;

    public function getIdprod(): ?int
    {
        return $this->idprod;
    }

    public function getNomprod(): ?string
    {
        return $this->nomprod;
    }

    public function setNomprod(string $nomprod): self
    {
        $this->nomprod = $nomprod;

        return $this;
    }

    public function getPrixprod(): ?float
    {
        return $this->prixprod;
    }

    public function setPrixprod(float $prixprod): self
    {
        $this->prixprod = $prixprod;

        return $this;
    }

    public function getImgprod(): ?string
    {
        return $this->imgprod;
    }

    public function setImgprod(string $imgprod): self
    {
        $this->imgprod = $imgprod;

        return $this;
    }

    public function getQuantprod(): ?int
    {
        return $this->quantprod;
    }

    public function setQuantprod(int $quantprod): self
    {
        $this->quantprod = $quantprod;

        return $this;
    }

    public function getDescprod(): ?string
    {
        return $this->descprod;
    }

    public function setDescprod(string $descprod): self
    {
        $this->descprod = $descprod;

        return $this;
    }
}
