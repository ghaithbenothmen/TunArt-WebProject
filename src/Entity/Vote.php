<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Repository\CandidatureRepository;

#[ORM\Entity(repositoryClass: App\Repository\CandidatureRepository::class)]
class Vote
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idVote = null;

    #[ORM\Column(type: 'date', length: 50)]
    private ?DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'Vote')]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'Candidature')]
    private ?Concours $idConcours = null;


}
