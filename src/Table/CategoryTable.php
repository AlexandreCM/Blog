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

    public function update(Category $category): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug WHERE id = :id;");
        $result = $query->execute([
            'id' => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ]);
        if (!$result) {
            throw new Exception("Impossible de modifier l'enregistrement #$id dans la table {$this->table}");
        }
    }

    public function create(Category $category)
    {
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET name = :name, slug = :slug;");
        $result = $query->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ]);
        if (!$result) {
            throw new Exception("Impossible de creer l'enregistrement dans la table {$this->table}");
        }
        $category->setId($this->pdo->lastInsertId());
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?;");
        $result = $query->execute([$id]);
        if (!$result) {
            throw new Exception("Impossible de supprimer l'enregistrement #$id dans la table {$this->table}");
        }
    }

}