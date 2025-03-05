<?php

namespace App\Entity;

use App\Repository\RunningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: RunningRepository::class)]
class Running
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["running:read", "running_participant:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["running:read"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["running:read"])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(["running:read"])]
    private ?string $location = null;

    /**
     * Relación OneToMany con RunningParticipant
     */
    #[ORM\OneToMany(mappedBy: 'running', targetEntity: RunningParticipant::class)]
    #[MaxDepth(1)]
    #[Groups(["running:read"])]
    private Collection $runningParticipants;

    public function __construct()
    {
        $this->runningParticipants = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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
            $runningParticipant->setRunning($this);
        }

        return $this;
    }

    public function removeRunningParticipant(RunningParticipant $runningParticipant): static
    {
        if ($this->runningParticipants->removeElement($runningParticipant)) {
            // set the owning side to null (unless already changed)
            if ($runningParticipant->getRunning() === $this) {
                $runningParticipant->setRunning(null);
            }
        }

        return $this;
    }
}
