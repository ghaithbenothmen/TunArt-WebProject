<?php

namespace App\Entity;

use App\Repository\LikeactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeactRepository::class)]
#[ORM\Table(name: 'like')]
class Likeact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}