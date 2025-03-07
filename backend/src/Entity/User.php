<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user:read", "user:new", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(["user:read", "user:new", "user_basic:read", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?string $email = null;


    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(["user:read", "user:new", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    // #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:read", "user_basic:read", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]  // Add user_basic:read
    private ?string $name = null;
    
    #[ORM\Column]
    #[Groups(['user:read', "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?bool $banned = false;

    /**
     * @var Collection<int, CyclingParticipant>
     */
    #[ORM\OneToMany(targetEntity: CyclingParticipant::class, mappedBy: 'user')]
    #[Groups("user:read")]
    private Collection $cyclingParticipants;

    /**
     * @var Collection<int, RunningParticipant>
     */
    #[ORM\OneToMany(targetEntity: RunningParticipant::class, mappedBy: 'user')]
    #[Groups("user:read")]
    private Collection $runningParticipants;

    /**
     * @var Collection<int, TrailRunningParticipant>
     */
    #[ORM\OneToMany(targetEntity: TrailRunningParticipant::class, mappedBy: 'user')]
    #[Groups("user:read")]
    private Collection $trailRunningParticipants;

    #[ORM\Column]
    #[Groups(["user:read", "user:new", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?int $age = 0;

    #[ORM\Column(length: 1)]
    #[Groups(["user:read", "user:new", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?string $gender = 'M';

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["user:read", "user:new", "cycling:read", "cycling_participant:read", "running:read", "running_participant:read", "trail_running:read", "trail_running_participant:read"])]
    private ?string $image = 'default.png';

    public function __construct()
    {
        $this->cyclingParticipants = new ArrayCollection();
        $this->runningParticipants = new ArrayCollection();
        $this->trailRunningParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): static
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * @return Collection<int, CyclingParticipant>
     */
    public function getCyclingParticipants(): Collection
    {
        return $this->cyclingParticipants;
    }

    public function addCyclingParticipant(CyclingParticipant $cyclingParticipant): static
    {
        if (!$this->cyclingParticipants->contains($cyclingParticipant)) {
            $this->cyclingParticipants->add($cyclingParticipant);
            $cyclingParticipant->setUser($this);
        }

        return $this;
    }

    public function removeCyclingParticipant(CyclingParticipant $cyclingParticipant): static
    {
        if ($this->cyclingParticipants->removeElement($cyclingParticipant)) {
            // set the owning side to null (unless already changed)
            if ($cyclingParticipant->getUser() === $this) {
                $cyclingParticipant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RunningParticipant>
     */
    public function getRunningParticipants(): Collection
    {
        return $this->runningParticipants;
    }

    public function addRunningParticipant(RunningParticipant $runningParticipant): static
    {
        if (!$this->runningParticipants->contains($runningParticipant)) {
            $this->runningParticipants->add($runningParticipant);
            $runningParticipant->setUser($this);
        }

        return $this;
    }

    public function removeRunningParticipant(RunningParticipant $runningParticipant): static
    {
        if ($this->runningParticipants->removeElement($runningParticipant)) {
            // set the owning side to null (unless already changed)
            if ($runningParticipant->getUser() === $this) {
                $runningParticipant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrailRunningParticipant>
     */
    public function getTrailRunningParticipants(): Collection
    {
        return $this->trailRunningParticipants;
    }

    public function addTrailRunningParticipant(TrailRunningParticipant $trailRunningParticipant): static
    {
        if (!$this->trailRunningParticipants->contains($trailRunningParticipant)) {
            $this->trailRunningParticipants->add($trailRunningParticipant);
            $trailRunningParticipant->setUser($this);
        }

        return $this;
    }

    public function removeTrailRunningParticipant(TrailRunningParticipant $trailRunningParticipant): static
    {
        if ($this->trailRunningParticipants->removeElement($trailRunningParticipant)) {
            // set the owning side to null (unless already changed)
            if ($trailRunningParticipant->getUser() === $this) {
                $trailRunningParticipant->setUser(null);
            }
        }

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

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
}
