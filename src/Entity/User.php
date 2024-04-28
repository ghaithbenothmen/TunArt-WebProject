<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\UserRepository;



#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $user = null;

    


    public function getIdUser(): ?int
    {
        return $this->user;
    }

    public function setIIdUser(?int $id): static
    {
        $this->user = $id;

        return $this;
    }


}
