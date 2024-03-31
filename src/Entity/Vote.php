<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'vote')]
#[ORM\Entity(repositoryClass: App\Repository\VoteRepository::class)]
#[ORM\Index(name: 'Ref_Concours_FK', columns: ['ID_concours'])]
#[ORM\Index(name: 'ID_User_FK', columns: ['ID_user'])]

class Vote
{
    #[ORM\Column(name: 'ID_vote', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $idVote;
    

    #[ORM\Column(name: 'Date', type: 'date', nullable: false)]
    private \DateTime $date;
    

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'ID_user', referencedColumnName: 'ID_user')]
    private User $idUser;
    

    #[ORM\ManyToOne(targetEntity: Concours::class)]
    #[ORM\JoinColumn(name: 'ID_concours', referencedColumnName: 'Refrence')]
    private Concours $idConcours;
    

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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdConcours(): ?Concours
    {
        return $this->idConcours;
    }

    public function setIdConcours(?Concours $idConcours): static
    {
        $this->idConcours = $idConcours;

        return $this;
    }


}
