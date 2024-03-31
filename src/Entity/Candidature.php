<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'candidature')]
#[ORM\Entity(repositoryClass: App\Repository\CandidatureRepository::class)]
#[ORM\Index(name: 'FK_CONCOURS_ID', columns: ['ID_concours'])]
#[ORM\Index(name: 'FK_USER_ID', columns: ['ID_user'])]
class Candidature
{
    #[ORM\Column(name: 'ID_candidature', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $idCandidature;
    

    #[ORM\Column(name: 'Date', type: 'date', nullable: false)]
    private \DateTime $date;
    

    #[ORM\ManyToOne(targetEntity: Concours::class)]
    #[ORM\JoinColumn(name: 'ID_concours', referencedColumnName: 'Refrence')]
    private Concours $idConcours;
    

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'ID_user', referencedColumnName: 'ID_user')]
    private User $idUser;
    

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
        return $this->idConcours;
    }

    public function setIdConcours(?Concours $idConcours): static
    {
        $this->idConcours = $idConcours;

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


}
