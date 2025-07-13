<?php

namespace App\Entity;

use App\Enum\TaskCategory;
use App\Enum\TaskInterval;
use App\Enum\TaskMode;
use App\Enum\TaskPriority;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRef = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Topic $TopicIDRef = null;

    /**
     * @var Collection<int, Todo>
     */
    #[ORM\OneToMany(targetEntity: Todo::class, mappedBy: 'TaskIDRef', orphanRemoval: true)]
    private Collection $todos;

    #[ORM\Column]
    private ?bool $isCompleted = null;

    #[ORM\Column(enumType: TaskMode::class)]
    private ?TaskMode $mode = null;

    #[ORM\Column(enumType: TaskCategory::class)]
    private ?TaskCategory $category = null;

    #[ORM\Column(enumType: TaskInterval::class)]
    private ?TaskInterval $interval = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $deadlineDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deadlineTime = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(enumType: TaskPriority::class)]
    private ?TaskPriority $priority = null;

    public function __construct()
    {
        $this->todos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUserRef(): ?User
    {
        return $this->userRef;
    }

    public function setUserRef(?User $userRef): static
    {
        $this->userRef = $userRef;

        return $this;
    }

    public function getTopicIDRef(): ?Topic
    {
        return $this->TopicIDRef;
    }

    public function setTopicIDRef(?Topic $TopicIDRef): static
    {
        $this->TopicIDRef = $TopicIDRef;

        return $this;
    }

    /**
     * @return Collection<int, Todo>
     */
    public function getTodos(): Collection
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo): static
    {
        if (!$this->todos->contains($todo)) {
            $this->todos->add($todo);
            $todo->setTaskIDRef($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): static
    {
        if ($this->todos->removeElement($todo)) {
            // set the owning side to null (unless already changed)
            if ($todo->getTaskIDRef() === $this) {
                $todo->setTaskIDRef(null);
            }
        }

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getMode(): ?TaskMode
    {
        return $this->mode;
    }

    public function setMode(TaskMode $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getCategory(): ?TaskCategory
    {
        return $this->category;
    }

    public function setCategory(TaskCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getInterval(): ?TaskInterval
    {
        return $this->interval;
    }

    public function setInterval(TaskInterval $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    public function getDeadlineDate(): ?\DateTime
    {
        return $this->deadlineDate;
    }

    public function setDeadlineDate(?\DateTime $deadlineDate): static
    {
        $this->deadlineDate = $deadlineDate;

        return $this;
    }

    public function getDeadlineTime(): ?string
    {
        return $this->deadlineTime;
    }

    public function setDeadlineTime(?string $deadlineTime): static
    {
        $this->deadlineTime = $deadlineTime;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(TaskPriority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}
