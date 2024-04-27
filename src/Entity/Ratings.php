<?php

namespace App\Entity;
use App\Repository\RatingsRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RatingsRepository::class)]
class Ratings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $ratingValue = null;

    #[ORM\ManyToOne(inversedBy: 'formation_id')]
    private ?Formation $formation = null;

    #[ORM\ManyToOne(inversedBy: 'user_id')]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingValue(): ?float
    {
        return $this->ratingValue;
    }

    public function setRatingValue(float $ratingValue): static
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }


}
