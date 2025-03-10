<?php

namespace App\Entity;

use App\Repository\TrailRunningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


#[ORM\Entity(repositoryClass: TrailRunningRepository::class)]
class TrailRunning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?int $distance_km = null;

    #[ORM\Column(length: 255)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $coordinates = null;

    #[ORM\Column]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?int $unevenness = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?int $entry_fee = null;

    #[ORM\Column]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?int $available_slots = null;

    #[ORM\Column(length: 255)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $image = null;

    #[ORM\Column(length: 1)]
    #[Groups(["trail_running:read", "trail_running_participant:read", "user:read"])]
    private ?string $gender = null;

    /**
     * @var Collection<int, TrailRunningParticipant>
     */
    #[ORM\OneToMany(targetEntity: TrailRunningParticipant::class, mappedBy: 'trailRunning')]
    #[MaxDepth(2)]
    #[Groups(["trail_running:read"])]
    private Collection $trailRunningParticipants;

    public function __construct()
    {
        $this->trailRunningParticipants = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDistanceKm(): ?int
    {
        return $this->distance_km;
    }

    public function setDistanceKm(int $distance_km): static
    {
        $this->distance_km = $distance_km;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getUnevenness(): ?int
    {
        return $this->unevenness;
    }

    public function setUnevenness(int $unevenness): static
    {
        $this->unevenness = $unevenness;

        return $this;
    }

    public function getEntryFee(): ?int
    {
        return $this->entry_fee;
    }

    public function setEntryFee(?int $entry_fee): static
    {
        $this->entry_fee = $entry_fee;

        return $this;
    }

    public function getAvailableSlots(): ?int
    {
        return $this->available_slots;
    }

    public function setAvailableSlots(int $available_slots): static
    {
        $this->available_slots = $available_slots;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
            $trailRunningParticipant->setTrailRunning($this);
        }

        return $this;
    }

    public function removeTrailRunningParticipant(TrailRunningParticipant $trailRunningParticipant): static
    {
        if ($this->trailRunningParticipants->removeElement($trailRunningParticipant)) {
            // set the owning side to null (unless already changed)
            if ($trailRunningParticipant->getTrailRunning() === $this) {
                $trailRunningParticipant->setTrailRunning(null);
            }
        }

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
}
