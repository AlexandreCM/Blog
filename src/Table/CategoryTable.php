<?php
namespace App\Table;

use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;
use PDO;

final class CategoryTable extends Table {

    protected $table = 'category';
    protected $class = Category::class;

    /** @param Post[] $posts */
    public function hydratePosts(array $posts): void
    {
        $postsById = [];
        foreach ($posts as $post) {
            $postsById[$post->getId()] = $post;
        }
        /** @var Category[] $categories */
        $categories = $this->pdo
            ->query('SELECT c.*, pc.post_id 
                    FROM post_category pc 
                    JOIN ' . $this->table . ' c ON c.id = pc.category_id 
                    WHERE pc.post_id IN (' . implode(',', array_keys($postsById)) . ');')
            ->fetchAll(PDO::FETCH_CLASS, $this->class);

        foreach ($categories as $category) {
            $postsById[$category->getPostId()]->addCategory($category);
        }
    }

    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table}",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo
        );
        /** @var Category[] $categories */
        $categories = $paginatedQuery->getItems($this->class);
//        (new CategoryTable($this->pdo))->hydratePosts($categories);
        return [$categories, $paginatedQuery];
    }

}