<?php
namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post {

    private $id;
    private $name;
    private $slug;
    private $content;
    private $created_at;
    private array $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getName(): ?string
    {
        return htmlentities($this->name);
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }
    public function getExcerpt(): ?string
    {
        if ($this->content === null) {
            return null;
        }
        return Text::excerpt($this->content, 60);
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }
    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }


    /** @return Category[] */
    public function getCategories(): array
    {
        return $this->categories;
    }
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
        $category->setPost($this);
    }
    
}