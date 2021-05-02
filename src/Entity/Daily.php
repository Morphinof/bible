<?php

declare(strict_types=1);

namespace Bible\Entity;

use Bible\Entity\Traits\OwnerTrait;
use Bible\Repository\DailyRepository;
use Bible\Entity\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=DailyRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Daily
{
    use EntityTrait;
    use OwnerTrait;

    /**
     * @ORM\ManyToMany(targetEntity=Task::class)
     * @ORM\JoinTable(
     *     name="dailies_tags",
     *     joinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private Collection $tasks;

    #[Pure] public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        $this->tasks->removeElement($task);

        return $this;
    }
}
