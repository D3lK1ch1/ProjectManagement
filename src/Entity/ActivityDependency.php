<?php

namespace App\Entity;

use App\Repository\ActivityDependencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityDependencyRepository::class)]
class ActivityDependency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'incomingDependencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $successor = null;

    #[ORM\ManyToOne(inversedBy: 'outgoingDependencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $predecessor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuccessor(): ?Activity
    {
        return $this->successor;
    }

    public function setSuccessor(?Activity $successor): static
    {
        $this->successor = $successor;

        return $this;
    }

    public function getPredecessor(): ?Activity
    {
        return $this->predecessor;
    }

    public function setPredecessor(?Activity $predecessor): static
    {
        $this->predecessor = $predecessor;

        return $this;
    }
}
