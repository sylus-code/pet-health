<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 */
class Animal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sex;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $species;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $breed;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Prevention::class, mappedBy="animal", orphanRemoval=true)
     */
    private $preventions;

    /**
     * @ORM\OneToMany(targetEntity=Visit::class, mappedBy="animal", orphanRemoval=true)
     */
    private $visits;

    /**
     * @ORM\OneToMany(targetEntity=Symptom::class, mappedBy="animal", orphanRemoval=true)
     */
    private $symptoms;

    public function __construct()
    {
        $this->preventions = new ArrayCollection();
        $this->visits = new ArrayCollection();
        $this->symptoms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(?string $breed): self
    {
        $this->breed = $breed;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Prevention[]
     */
    public function getPreventions(): Collection
    {
        return $this->preventions;
    }

    public function addPrevention(Prevention $prevention): self
    {
        if (!$this->preventions->contains($prevention)) {
            $this->preventions[] = $prevention;
            $prevention->setAnimal($this);
        }

        return $this;
    }

    public function removePrevention(Prevention $prevention): self
    {
        if ($this->preventions->removeElement($prevention)) {
            // set the owning side to null (unless already changed)
            if ($prevention->getAnimal() === $this) {
                $prevention->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits[] = $visit;
            $visit->setAnimal($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getAnimal() === $this) {
                $visit->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Symptom[]
     */
    public function getSymptoms(): Collection
    {
        return $this->symptoms;
    }

    public function addSymptom(Symptom $symptom): self
    {
        if (!$this->symptoms->contains($symptom)) {
            $this->symptoms[] = $symptom;
            $symptom->setAnimal($this);
        }

        return $this;
    }

    public function removeSymptom(Symptom $symptom): self
    {
        if ($this->symptoms->removeElement($symptom)) {
            // set the owning side to null (unless already changed)
            if ($symptom->getAnimal() === $this) {
                $symptom->setAnimal(null);
            }
        }

        return $this;
    }
}
