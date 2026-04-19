<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $duration = null;

    /**
     * @var Collection<int, ActivityDependency>
     */
    #[ORM\OneToMany(targetEntity: ActivityDependency::class, mappedBy: 'successor')]
    private Collection $incomingDependencies;
    #[ORM\OneToMany(mappedBy: 'predecessor', targetEntity: ActivityDependency::class, cascade: ['persist', 'remove'])]
    private Collection $outgoingDependencies;

    public function __construct()
    {
        $this->incomingDependencies = new ArrayCollection();
        $this->outgoingDependencies = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
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

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, ActivityDependency>
     */
    public function getIncomingDependencies(): Collection
    {
        return $this->incomingDependencies;
    }

    public function addIncomingDependency(ActivityDependency $incomingDependency): static
    {
        if (!$this->incomingDependencies->contains($incomingDependency)) {
            $this->incomingDependencies->add($incomingDependency);
            $incomingDependency->setSuccessor($this);
        }

        return $this;
    }

    public function removeIncomingDependency(ActivityDependency $incomingDependency): static
    {
        if ($this->incomingDependencies->removeElement($incomingDependency)) {
            // set the owning side to null (unless already changed)
            if ($incomingDependency->getSuccessor() === $this) {
                $incomingDependency->setSuccessor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityDependency>
     */
    public function getOutgoingDependencies(): Collection
    {
        return $this->outgoingDependencies; 
    }

    public function addOutgoingDependency(ActivityDependency $outgoingDependency): static
    {
        if (!$this->outgoingDependencies->contains($outgoingDependency)) {
            $this->outgoingDependencies->add($outgoingDependency);
            $outgoingDependency->setPredecessor($this);
        }

        return $this;
    }

    public function removeOutgoingDependency(ActivityDependency $outgoingDependency): static
    {
        if ($this->outgoingDependencies->removeElement($outgoingDependency)) {
            // set the owning side to null (unless already changed)
            if ($outgoingDependency->getPredecessor() === $this) {
                $outgoingDependency->setPredecessor(null);
            }
        }

        return $this;
    }

}
