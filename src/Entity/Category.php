<?php

declare(strict_types=1);

namespace Bible\Entity;

use Bible\Repository\CategoryRepository;
use Bible\Entity\Traits\EntityTrait;
use Bible\Entity\Traits\NameTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    use EntityTrait;
    use NameTrait;

    /**
     * @ORM\OneToOne(targetEntity=Category::class, cascade={"persist", "remove"})
     */
    private ?Category $parent;

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
