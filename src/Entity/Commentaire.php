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

    #[ORM\Column(length: 250)]
    private ?string $contenuc = null;

    /**
     * @var \Actualite
     *
     * @ORM\ManyToOne(targetEntity="Actualite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_act", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Actualite $idAct = null;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="ID")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?User $idUser = null;

    public function getIdC(): ?int
    {
        return $this->idC;
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

    public function getIdAct(): ?Actualite
    {
        return $this->idAct;
    }

    public function setIdAct(?Actualite $idAct): static
    {
        $this->idAct = $idAct;

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
