<?php

declare(strict_types=1);

namespace Bible\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TitleTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}