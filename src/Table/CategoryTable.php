<?php
namespace App\Table;

use App\Model\Category;
use App\Model\Post;
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

    public function create(Category $category): void
    {
        $id = $this->createTable([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ]);
        $category->setId($id);
    }

    public function update(Category $category): void
    {
        $this->updateTable([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug()
        ], $category->getId());
    }

}