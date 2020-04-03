<?php

use App\Model\Category;
use App\Model\Post;
use App\Connection;
use App\PaginatedQuery;

$title = 'Mon Blog';
$pdo = Connection::getPDO();
$paginatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post"
);
/** @var Post[] $posts */
$posts = $paginatedQuery->getItems(Post::class);

$postsById = [];
foreach ($posts as $post) {
    $postsById[$post->getId()] = $post;
}

$categories = $pdo
    ->query('SELECT c.*, pc.post_id 
            FROM post_category pc 
            JOIN category c ON c.id = pc.category_id 
            WHERE pc.post_id IN (' . implode(',', array_keys($postsById)) . ');')
    ->fetchAll(PDO::FETCH_CLASS, Category::class);

foreach ($categories as $category) {
    $postsById[$category->getPostId()]->addCategory($category);
}

$link = $router->url('home');
?>
<h1>Mon Blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
    <div class="col-md-4">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousPage($link); ?>
    <?= $paginatedQuery->nextPage($link); ?>
</div>