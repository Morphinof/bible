<?php

declare(strict_types=1);

namespace Bible\Entity;

use Bible\Repository\ResetPasswordRequestRepository;
use Bible\Entity\Traits\EntityTrait;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use EntityTrait;
    use ResetPasswordRequestTrait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private object $user;

    public function __construct(object $user, DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getUser(): object
    {
        return $this->user;
    }
}
