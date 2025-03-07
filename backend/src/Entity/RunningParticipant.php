<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RunningParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: RunningParticipantRepository::class)]
#[ApiResource]
class RunningParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["running_participant:read", "user:read", "running:read"])]
    private ?int $id = null;

    /**
     * Relación ManyToOne con User
     */
    #[ORM\ManyToOne(inversedBy: 'runningParticipants')]
    #[MaxDepth(1)] // Limita la profundidad de la serialización
    #[Groups(["running_participant:read", "running:read"])]
    private ?User $user = null;

    /**
     * Relación ManyToOne con Running
     */
    #[ORM\ManyToOne(inversedBy: 'runningParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["running_participant:read", "user:read"])]  // Use a different group than "running:read"
    private ?Running $running = null;

    /**
     * Propiedad time
     */
    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(["running_participant:read", "user:read", "running:read"])]
    private ?string $time = null;

    /**
     * Propiedad dorsal
     */
    #[ORM\Column]
    #[Groups(["running_participant:read", "user:read", "running:read"])]
    private ?int $dorsal = null;

    /**
     * Propiedad banned
     */
    #[ORM\Column]
    #[Groups(["running_participant:read", "user:read", "running:read"])]
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

    public function getRunning(): ?Running
    {
        return $this->running;
    }

    public function setRunning(?Running $running): static
    {
        $this->running = $running;
        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): static
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
