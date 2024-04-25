<?php

namespace App\Entity;

use App\Entity\Concours;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\CandidatureRepository;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]


class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCandidature = null;

    #[ORM\Column(type: 'date', length: 50)]
    private ?DateTime $date = null ;

    #[ORM\ManyToOne(targetEntity: Concours::class , inversedBy: 'Candidature')]
    #[ORM\JoinColumn(name: "idConcours",referencedColumnName: "refrence")]
    private ?Concours $Concours = null;

    
    #[ORM\ManyToOne(targetEntity: User::class ,inversedBy: 'Candidature')]
    #[ORM\JoinColumn(name: "idUser",referencedColumnName: "user")]
    private ?User $user = null;
    
    
    public function getIdCandidature(): ?int
    {
        return $this->idCandidature;
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

    public function getIdConcours(): ?Concours
    {
        return $this->Concours;
    }

    public function setIdConcours(?Concours $Concours): static
    {
        $this->idConcours = $Concours;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->user;
    }

    public function setIdUser(?User $user): static
    {
        $this->idUser = $user;

        return $this;
    }

    public function __construct(DateTime $currentDate)
    {
        $this->date = $currentDate;
    }
    
}
