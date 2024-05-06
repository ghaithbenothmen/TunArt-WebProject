<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom_Oeuvre", type: "string", length: 255, nullable: false)]
    private $nomOeuvre;

    #[ORM\Column(name: "img", type: "string", length: 255, nullable: false)]
    private $img;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private $description;

    #[ORM\Column(name: "TypeOeuvre", type: "string", length: 2555, nullable: false)]
    private $typeoeuvre;

    #[ORM\Column(name: "date_Publication", type: "date", nullable: false)]
    private $datePublication;

    #[ORM\Column(name: "note", type: "integer", nullable: false)]
    private $note;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "artiste_id", referencedColumnName: "id")]
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
