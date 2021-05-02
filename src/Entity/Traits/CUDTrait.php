<?php

declare(strict_types=1);

namespace Bible\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

trait CUDTrait
{
    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $deletedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $date): self
    {
        $this->createdAt = $date;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $date): self
    {
        $this->updatedAt = $date;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeInterface $date): self
    {
        $this->deletedAt = $date;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
        if ($this->createdAt === null) {
            $this->createdAt = new DateTime('now');
        }
    }
}