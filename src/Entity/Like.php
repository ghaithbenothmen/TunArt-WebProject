<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Like
 *
 * @ORM\Table(name="like", indexes={@ORM\Index(name="fk_like", columns={"id_user"}), @ORM\Index(name="fk_oeuvre", columns={"id_oeuvre"})})
 * @ORM\Entity
 */
class Like
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_oeuvre", type="integer", nullable=false)
     */
    private $idOeuvre;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="LikeCount", type="integer", nullable=false)
     */
    private $likecount;

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
