<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Oeuvre
 *
 * @ORM\Table(name="oeuvre", indexes={@ORM\Index(name="fk_artiste", columns={"artiste_id"})})
 * @ORM\Entity
 */
class Oeuvre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_Oeuvre", type="string", length=255, nullable=false)
     */
    private $nomOeuvre;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255, nullable=false)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="TypeOeuvre", type="string", length=2555, nullable=false)
     */
    private $typeoeuvre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Publication", type="date", nullable=false)
     */
    private $datePublication;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private $note;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artiste_id", referencedColumnName="ID")
     * })
     */
    private $artiste;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOeuvre(): ?string
    {
        return $this->nomOeuvre;
    }

    public function setNomOeuvre(string $nomOeuvre): static
    {
        $this->nomOeuvre = $nomOeuvre;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeoeuvre(): ?string
    {
        return $this->typeoeuvre;
    }

    public function setTypeoeuvre(string $typeoeuvre): static
    {
        $this->typeoeuvre = $typeoeuvre;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getArtisteId(): ?User
    {
        return $this->artiste;
    }

    public function setArtisteId(?User $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    }


}
