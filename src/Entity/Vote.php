<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\VoteRepository;

#[ORM\Entity(repositoryClass: App\Repository\CandidatureRepository::class)]
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

    #[ORM\ManyToOne(inversedBy: 'Candidature')]
    private ?Concours $idConcours = null;


}
