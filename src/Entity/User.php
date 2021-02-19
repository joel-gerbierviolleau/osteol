<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="createdBy")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="createdOnBehalfOf")
     */
    private $patientsCreatedForMe;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="createdBy")
     */
    private $consultations;

    public function __construct()
    {
        $this->createdOnBehalfOf = new ArrayCollection();
        $this->patientsCreatedForMe = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatients(Patient $patients): self
    {
        if (!$this->patients->contains($patients)) {
            $this->patients[] = $patients;
            $patients->setCreatedBy($this);
        }

        return $this;
    }

    public function removePatients(Patient $patients): self
    {
        if ($this->patients->removeElement($patients)) {
            // set the owning side to null (unless already changed)
            if ($patients->getCreatedBy() === $this) {
                $patients->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatientsCreatedForMe(): Collection
    {
        return $this->patientsCreatedForMe;
    }

    public function addPatientsCreatedForMe(Patient $patientsCreatedForMe): self
    {
        if (!$this->patientsCreatedForMe->contains($patientsCreatedForMe)) {
            $this->patientsCreatedForMe[] = $patientsCreatedForMe;
            $patientsCreatedForMe->setCreatedOnBehalfOf($this);
        }

        return $this;
    }

    public function removePatientsCreatedForMe(Patient $patientsCreatedForMe): self
    {
        if ($this->patientsCreatedForMe->removeElement($patientsCreatedForMe)) {
            // set the owning side to null (unless already changed)
            if ($patientsCreatedForMe->getCreatedOnBehalfOf() === $this) {
                $patientsCreatedForMe->setCreatedOnBehalfOf(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getCreatedBy() === $this) {
                $consultation->setCreatedBy(null);
            }
        }

        return $this;
    }
}
