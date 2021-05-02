<?php

declare(strict_types=1);

namespace Bible\Entity;

use Bible\Entity\Traits\OwnerTrait;
use Bible\Repository\TaskRepository;
use Bible\Entity\Traits\EntityTrait;
use Bible\Entity\Traits\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    public const STATUS_STUCK = 'stuck';
    public const STATUS_ONGOING = 'on-going';
    public const STATUS_REVIEW = 'review';
    public const STATUS_COMPLETE = 'complete';

    public const STATUSES = [
        self::STATUS_STUCK,
        self::STATUS_ONGOING,
        self::STATUS_REVIEW,
        self::STATUS_COMPLETE,
    ];

    public const FORM_STATUSES = [
        'Stuck' => self::STATUS_STUCK,
        'On-going' => self::STATUS_ONGOING,
        'Review' => self::STATUS_REVIEW,
        'Complete' => self::STATUS_COMPLETE,
    ];

    use EntityTrait;
    use TitleTrait;
    use OwnerTrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Length(
     *      min=1,
     *      max=10,
     *      minMessage="Issue must be at least {{ limit }} digit long",
     *      maxMessage="Issue cannot be longer than {{ limit }} digits"
     * )
     */
    private ?int $issue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;

    public function __construct(?string $title = null, ?string $status = self::STATUS_ONGOING, ?string $notes = null)
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid task status "%s"', $status));
        }

        $this->title = $title;
        $this->status = $status;
        $this->notes = $notes;
    }

    public function getIssue(): ?int
    {
        return $this->issue;
    }

    public function setIssue(?int $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('#%d / %s', $this->issue, $this->title);
    }
}
