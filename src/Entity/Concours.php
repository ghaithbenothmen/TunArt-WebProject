<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\ConcoursRepository;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ConcoursRepository::class)]
class Concours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $refrence = null;


    #[ORM\Column(type: 'date', length: 50)]
    #[Assert\NotBlank(message:'date obligatoire' )]
    #[Assert\GreaterThanOrEqual("today", message: 'date invalide')]
    private ?DateTime $date = null ;


    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:'type obligatoire' )]
    private ?string $type = null;
    

    #[ORM\Column]
    #[Assert\NotBlank(message:'prix obligatoire' )]
    #[Assert\Regex(
        pattern: '/^-?\d+$/',
        message: 'Le contenu peut etre uniquement des chiffres'
    )]
    private ?int $prix = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'lien obligatoire' )]
    private ?string $lien;


    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:'nom obligatoire' )]
    private ?string $nom;
    

    public function getRefrence(): ?int
    {
        return $this->refrence;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): static
    {
        $this->lien = $lien;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function __construct(DateTime $currentDate)
    {
        $this->date = $currentDate;
    }
}
