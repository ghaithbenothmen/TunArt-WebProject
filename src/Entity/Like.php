<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    private ?int $idUser;

    #[ORM\Column]
    private ?int $idOeuvre;

    #[ORM\Column]
    private ?bool $etat;

    #[ORM\Column]
    private ?int $likecount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdOeuvre(): ?int
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(int $idOeuvre): static
    {
        $this->idOeuvre = $idOeuvre;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLikecount(): ?int
    {
        return $this->likecount;
    }

    public function setLikecount(int $likecount): static
    {
        $this->likecount = $likecount;

        return $this;
    }
}
