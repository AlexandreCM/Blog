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
    private $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return htmlentities($this->name);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
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