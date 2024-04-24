<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Candidature
 *
 * @ORM\Table(name="candidature", indexes={@ORM\Index(name="FK_USER_ID", columns={"ID_user"}), @ORM\Index(name="FK_CONCOURS_ID", columns={"ID_concours"})})
 * @ORM\Entity
 */
class Candidature
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_candidature", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCandidature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_concours", type="integer", nullable=false)
     */
    private $idConcours;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_user", type="integer", nullable=false)
     */
    private $idUser;

    public function getIdCandidature(): ?int
    {
        return $this->idCandidature;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdConcours(): ?int
    {
        return $this->idConcours;
    }

    public function setIdConcours(int $idConcours): static
    {
        $this->idConcours = $idConcours;

        return $this;
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


}
