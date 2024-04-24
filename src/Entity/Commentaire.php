<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="commentaire_fk_idAct", columns={"id_act"}), @ORM\Index(name="commentaire_fk_idUser", columns={"id_user"})})
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_c", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idC;

    /**
     * @var string
     *
     * @ORM\Column(name="contenuC", type="string", length=255, nullable=false)
     */
    private $contenuc;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="ID")
     * })
     */
    private $idUser;

    /**
     * @var \Actualite
     *
     * @ORM\ManyToOne(targetEntity="Actualite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_act", referencedColumnName="id")
     * })
     */
    private $idAct;

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

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

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


}
