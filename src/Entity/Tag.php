<?php

namespace Bible\Entity;

use Bible\Repository\TagRepository;
use Bible\Entity\Traits\EntityTrait;
use Bible\Entity\Traits\NameTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
{
    use EntityTrait;
    use NameTrait;
}
