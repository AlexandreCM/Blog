<?php
namespace App\Table;

use App\Model\Category;
use App\Model\Post;
use App\Table\Exception\NotFoundException;
use PDO;

class CategoryTable extends Table {

    public function find(int $id): Category
    {
        $query = $this->pdo->prepare('SELECT * FROM category WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $result = $query->fetch();
        if ($result == false) {
            throw new NotFoundException('category', $id);
        }
        return $result;
    }

    /** @param Post[] $post */
    public function hydratePosts(array $posts): void
    {
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
    }

}