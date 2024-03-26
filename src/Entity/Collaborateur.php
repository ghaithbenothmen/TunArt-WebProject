<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collaborateur
 *
 * @ORM\Table(name="collaborateur")
 * @ORM\Entity
 */
class Collaborateur
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NomComplet", type="string", length=100, nullable=false)
     */
    private $nomcomplet;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=100, nullable=false)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcomplet(): ?string
    {
        return $this->nomcomplet;
    }

    public function setNomcomplet(string $nomcomplet): static
    {
        $this->nomcomplet = $nomcomplet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


}
