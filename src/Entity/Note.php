<?php

declare(strict_types=1);

namespace Bible\Entity;

use Bible\Entity\Traits\OwnerTrait;
use Bible\Repository\NoteRepository;
use Bible\Entity\Traits\EntityTrait;
use Bible\Entity\Traits\TitleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Note
{
    use EntityTrait;
    use TitleTrait;
    use OwnerTrait;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private ?string $content;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     * @ORM\JoinTable(
     *     name="notes_categories",
     *     joinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private Collection $categories;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     * @ORM\JoinTable(
     *     name="notes_tags",
     *     joinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private Collection $tags;

    #[Pure] public function __construct(?string $title = null, ?string $content = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
