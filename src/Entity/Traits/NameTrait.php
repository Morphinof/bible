<?php

declare(strict_types=1);

namespace Bible\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameTrait
{
    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}