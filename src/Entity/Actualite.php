<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\ActualiteRepository;


#[ORM\Entity(repositoryClass: ActualiteRepository::class)]

class Actualite
{

     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
     private ?int $id = null;


    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 100)]
    private ?string $text = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    #[ORM\Column(type:"date", length: 50)]
    private ?DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


}
