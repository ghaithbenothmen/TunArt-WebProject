<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Actualite;
use App\Entity\User;
use App\Repository\CommentaireRepository;


#[ORM\Entity(repositoryClass: CommentaireRepository::class)]

class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idC = null;

    /**
     * @var \Actualite
     *
     * @ORM\ManyToOne(targetEntity="Actualite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_act", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Actualite $actualite = null;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="ID")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?User $user= null;

    #[ORM\Column(length: 250)]
    private ?string $contenuc = null;

    public function getIdC(): ?int
    {
        return $this->idC;
    }

    public function getActualite(): ?Actualite
    {
        return $this->actualite;
    }

    public function setActualite(?Actualite $actualite): static
    {
        $this->actualite = $actualite;

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

    public function getContenuc(): ?string
    {
        return $this->contenuc;
    }

    public function setContenuc(string $contenuc): static
    {
        $this->contenuc = $contenuc;

        return $this;
    }


}
