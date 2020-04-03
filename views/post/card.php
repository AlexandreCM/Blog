<?php
$categories = [];
foreach ($post->getCategories() as $category) {
    $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
    $categories[] = <<<HTML
        <a href="{$url}">{$category->getName()}</a>
    HTML;
}
?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?= $post->getName() ?></h5>
        <p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
        <p><?= implode(', ', $categories) ?></p>
        <p><?= $post->getExcerpt() ?></p>
        <p><a href="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a></p>
    </div>
</div>
