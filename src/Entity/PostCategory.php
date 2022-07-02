<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]
#[ApiResource]
class PostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: PostSubCategory::class)]
    private $postSubCategories;

    #[ORM\OneToMany(mappedBy: 'categoryId', targetEntity: Post::class)]
    private $posts;

    public function __construct()
    {
        $this->postSubCategories = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, PostSubCategory>
     */
    public function getPostSubCategories(): Collection
    {
        return $this->postSubCategories;
    }

    public function addPostSubCategory(PostSubCategory $postSubCategory): self
    {
        if (!$this->postSubCategories->contains($postSubCategory)) {
            $this->postSubCategories[] = $postSubCategory;
            $postSubCategory->setCategory($this);
        }

        return $this;
    }

    public function removePostSubCategory(PostSubCategory $postSubCategory): self
    {
        if ($this->postSubCategories->removeElement($postSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($postSubCategory->getCategory() === $this) {
                $postSubCategory->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategoryId($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategoryId() === $this) {
                $post->setCategoryId(null);
            }
        }

        return $this;
    }
}
