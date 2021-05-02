<?php

declare(strict_types=1);

namespace Bible\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    use CUDTrait;
}