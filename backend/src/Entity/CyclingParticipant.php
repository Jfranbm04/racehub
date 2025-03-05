<?php

namespace App\Entity;

use App\Repository\CyclingParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CyclingParticipantRepository::class)]
class CyclingParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["cycling_participant:read", "user:read"])]
    private ?int $id = null;

    /**
     * Relación ManyToOne con User
     */
    #[ORM\ManyToOne(inversedBy: 'cyclingParticipants')]
    #[MaxDepth(1)]
    #[Groups(["cycling_participant:read"])] // Remove user:read to prevent circular reference
    private ?User $user = null;

    /**
     * Relación ManyToOne con Cycling
     */
    #[ORM\ManyToOne(inversedBy: 'cyclingParticipants')]
    #[MaxDepth(1)]
    #[Groups("cycling_participant:read")]
    private ?Cycling $cycling = null;

    /**
     * Propiedad time
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["cycling_participant:read", "user:read"])]
    private ?\DateTimeInterface $time = null;

    /**
     * Propiedad dorsal
     */
    #[ORM\Column]
    #[Groups(["cycling_participant:read", "user:read"])]
    private ?int $dorsal = null;

    /**
     * Propiedad banned
     */
    #[ORM\Column]
    #[Groups(["cycling_participant:read", "user:read"])]
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

    public function getCycling(): ?Cycling
    {
        return $this->cycling;
    }

    public function setCycling(?Cycling $cycling): static
    {
        $this->cycling = $cycling;

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
