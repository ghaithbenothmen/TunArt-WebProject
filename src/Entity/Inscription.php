<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{


    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'formation_inscription')]
    private ?Formation $formation = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'user_inscription')]
    private ?User $user = null;

   


    public function getFormationId(): ?Formation
    {
        return $this->formation;
    }

    public function setFormationId(?Formation $formation_id): static
    {
        $this->formation = $formation_id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user = $user_id;

        return $this;
    }
}
