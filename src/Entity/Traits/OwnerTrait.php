<?php

declare(strict_types=1);

namespace Bible\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Bible\Entity\User;

trait OwnerTrait
{
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?UserInterface $owner;

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(UserInterface $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}