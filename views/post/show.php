<?php

use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int) $params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if ($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

?>

<h1 class="card-title"><?= $post->getName() ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
<?php foreach ($post->getCategories() as $category): ?>
<a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= $category->getName() ?></a>
<?php endforeach ?>
<p><?= $post->getContent() ?></p>
