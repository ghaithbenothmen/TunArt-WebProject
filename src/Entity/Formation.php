<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Entity\Categorie;
use App\Repository\FormationRepository;


#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column]
     private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;
    
    #[ORM\Column(length: 50)]
    private ?string $niveau = null;

    #[ORM\Column(type: 'date', length: 50)]
    private ?DateTime $datedebut = null;


    #[ORM\Column(type: 'date', length: 50)]
    private ?DateTime $datefin = null;

    #[ORM\Column(length: 100)]
    private ?string $image = null;

    #[ORM\Column(length: 200)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\ManyToOne(inversedBy: 'formation')]
    private ?Categorie $cat = null;
   
    #[ORM\ManyToOne(inversedBy: 'formation')]
    private ?User $artiste = null;

    #[ORM\OneToMany(mappedBy: 'formation_id', targetEntity: Inscription::class)]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Ratings::class)]
    private Collection $ratings;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /*
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "formation")]
    private $user;*/

    /**
     * Constructor
     */
   /*  public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    } */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCat(): ?Categorie
    {
        return $this->cat;
    }

    public function setCat(?Categorie $cat): static
    {
        $this->cat = $cat;

        return $this;
    }
     public function getArtiste(): ?User
    {
        return $this->artiste;
    }

    public function setArtiste(?User $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    } 

    /*** @return Collection<int, User>*/
    /* public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->addFormation($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            $user->removeFormation($this);
        }

        return $this;
    }
 */

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setFormationId($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getFormationId() === $this) {
                $inscription->setFormationId(null);
            }
        }

        return $this;
    }

    public function getRatingsAverage(): ?float
{
    $ratings = $this->ratings->toArray();
    $total = 0;
    $count = count($ratings);

    if ($count === 0) {
        return null;
    }

    foreach ($ratings as $rating) {
        $total += $rating->getRatingValue();
    }

    return $total / $count;
}
}
