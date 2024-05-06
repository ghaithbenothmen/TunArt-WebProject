<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\VoteRepository;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idVote = null;

    #[ORM\Column(type: 'date', length: 50)]
    private ?DateTime $date = null;

    #[ORM\ManyToOne(targetEntity: User::class ,inversedBy: 'Vote')]
    #[ORM\JoinColumn(name: "idUser",referencedColumnName: "user")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Concours::class , inversedBy: 'Vote')]
    #[ORM\JoinColumn(name: "idConcours",referencedColumnName: "refrence")]
    private ?Concours $Concours = null;

    public function getIdVote(): ?int
    {
        return $this->idVote;
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
        $this->Concours = $Concours;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->user;
    }

    public function setIdUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __construct(DateTime $currentDate,User $user,Concours $Concours)
    {
        $this->date = $currentDate;
        $this->user = $user;
        $this->Concours = $Concours;
    }
    
}
