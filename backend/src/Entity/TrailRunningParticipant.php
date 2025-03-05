<?php

namespace App\Entity;

use App\Repository\TrailRunningParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: TrailRunningParticipantRepository::class)]
class TrailRunningParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["trail_running_participant:read", "user:read"])]
    private ?int $id = null;

    /**
     * Relación ManyToOne con User
     */
    #[ORM\ManyToOne(inversedBy: 'trailRunningParticipants')]
    #[MaxDepth(1)]
    #[Groups(["trail_running_participant:read"])]
    private ?User $user = null;

    /**
     * Relación ManyToOne con TrailRunning
     */
    #[ORM\ManyToOne(inversedBy: 'trailRunningParticipants')]
    #[MaxDepth(1)]
    #[Groups("trail_running_participant:read")]
    private ?TrailRunning $trailRunning = null;

    /**
     * Propiedad time
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["trail_running_participant:read", "user:read"])]
    private ?\DateTimeInterface $time = null;

    /**
     * Propiedad dorsal
     */
    #[ORM\Column]
    #[Groups(["trail_running_participant:read", "user:read"])]
    private ?int $dorsal = null;

    /**
     * Propiedad banned
     */
    #[ORM\Column]
    #[Groups(["trail_running_participant:read", "user:read"])]
    private ?bool $banned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTrailRunning(): ?TrailRunning
    {
        return $this->trailRunning;
    }

    public function setTrailRunning(?TrailRunning $trailRunning): static
    {
        $this->trailRunning = $trailRunning;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDorsal(): ?int
    {
        return $this->dorsal;
    }

    public function setDorsal(int $dorsal): static
    {
        $this->dorsal = $dorsal;

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
}
