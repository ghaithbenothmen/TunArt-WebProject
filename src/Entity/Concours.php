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
    #[ORM\Column(name: 'Reference', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $reference;

    #[ORM\Column(name: 'Date', type: 'date', nullable: false)]
    private \DateTime $date;
    

    #[ORM\Column(name: 'Type', type: 'string', length: 20, nullable: false)]
    private string $type;
    

    #[ORM\Column(name: 'Prix', type: 'integer', nullable: false)]
    private int $prix;
    

    #[ORM\Column(name: 'Lien', type: 'string', length: 255, nullable: false)]
    private string $lien= null;
    

    #[ORM\Column(name: 'nom', type: 'string', length: 25, nullable: false)]
    private string $nom;

    
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


}
