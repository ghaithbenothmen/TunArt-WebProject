<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\ActualiteRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ActualiteRepository::class)]

class Actualite
{

     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
     private ?int $id = null;

    #[Assert\NotBlank(message: "Veuillez Remplir Titre ")]
    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[Assert\NotBlank(message: "Veuillez Remplir Text")]
    #[ORM\Column(length: 100)]
    private ?string $text = null;


    #[Assert\GreaterThanOrEqual("today", message: 'date invalide')]
    #[Assert\NotBlank(message: "Veuillez Remplir Date")]
    #[ORM\Column(type:"date", length: 50)]
    private ?DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: "integer")]
    private ?int $liked = 0;

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

    public function __construct(DateTime $cdate)
    {
        $this->date = $cdate;
    }

    public function getLiked(): ?int
    {
        return $this->liked;
    }

    public function setLiked(?int $liked): self
    {
        $this->liked = $liked;

        return $this;
    }
}
