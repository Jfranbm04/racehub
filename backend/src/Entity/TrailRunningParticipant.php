<?php

namespace App\Entity;

use App\Repository\TrailRunningParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrailRunningParticipantRepository::class)]
class TrailRunningParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trailRunningParticipants')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'trailRunningParticipants')]
    private ?TrailRunning $trailRunning = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column]
    private ?int $dorsal = null;

    #[ORM\Column(nullable: true)]
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

    public function setBanned(?bool $banned): static
    {
        $this->banned = $banned;

        return $this;
    }
}
