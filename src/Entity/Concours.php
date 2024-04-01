<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Concours
 *
 * @ORM\Table(name="concours")
 * @ORM\Entity(repositoryClass=App\Repository\ConcoursRepository::class)
 */

#[ORM\Table(name: 'concours')]
#[ORM\Entity(repositoryClass: ConcoursRepository::class)]
class Concours
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]

    private ?int $Refrence  = null;


    #[ORM\Column(name: 'Date', type: 'date', nullable: false)]
    private ?\DateTime $date = null;
    

    #[ORM\Column(name: 'Type', type: 'string', length: 20, nullable: false)]
    private ?string $type = null;
    

    #[ORM\Column(name: 'Prix', type: 'integer', nullable: false)]
    private ?int $prix = null;
    

    #[ORM\Column(name: 'Lien', type: 'string', length: 255, nullable: false)]
    private ?string $lien= null;
    

    #[ORM\Column(name: 'nom', type: 'string', length: 25, nullable: false)]
    private ?string $nom = null;

    
    public function getRfrence(): ?int
    {
        return $this->Refrence;
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


}
