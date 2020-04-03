<?php
namespace App\Model;

class Category {

    private int $id;
    private string $slug;
    private string $name;
    private int $post_id;
    private Post $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getName(): ?string
    {
        return htmlentities($this->name);
    }

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }





}