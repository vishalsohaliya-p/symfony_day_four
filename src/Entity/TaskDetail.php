<?php

namespace App\Entity;

use App\Repository\TaskDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskDetailRepository::class)]
class TaskDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $priority;

    #[ORM\Column]
    private float $estimatedHours;

    #[ORM\OneToOne(inversedBy: 'taskDetail', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getEstimatedHours(): float
    {
        return $this->estimatedHours;
    }

    public function setEstimatedHours(float $estimatedHours): static
    {
        $this->estimatedHours = $estimatedHours;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(Task $task): static
    {
        $this->task = $task;

        return $this;
    }
}
