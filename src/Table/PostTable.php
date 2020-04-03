<?php
namespace App\Table;

use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;
use PDO;

class PostTable {

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post",
            $this->pdo
        );
        /** @var Post[] $posts */
        $posts = $paginatedQuery->getItems(Post::class);

        $postsById = [];
        foreach ($posts as $post) {
            $postsById[$post->getId()] = $post;
        }

        $categories = $this->pdo
            ->query('SELECT c.*, pc.post_id 
                    FROM post_category pc 
                    JOIN category c ON c.id = pc.category_id 
                    WHERE pc.post_id IN (' . implode(',', array_keys($postsById)) . ');')
            ->fetchAll(PDO::FETCH_CLASS, Category::class);

        foreach ($categories as $category) {
            $postsById[$category->getPostId()]->addCategory($category);
        }
        return [$posts, $paginatedQuery];
    }

}