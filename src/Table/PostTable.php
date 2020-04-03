<?php
namespace App\Table;

use App\Model\Post;
use App\PaginatedQuery;

class PostTable extends Table {

    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post",
            $this->pdo
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function findPaginatedForCategory(int $categoryId)
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT p.* FROM post p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$categoryId}
            ORDER BY created_at DESC",
            "SELECT COUNT(post_id) FROM post_category WHERE category_id = {$categoryId}"
        );
        $posts = $paginatedQuery->getItems(Post::class);
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

}