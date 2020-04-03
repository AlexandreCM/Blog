<?php

use App\Connection;
use App\Table\PostTable;

$title = 'Mon Blog';

$pdo = Connection::getPDO();
$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();
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
    <?= $pagination->previousPage($link); ?>
    <?= $pagination->nextPage($link); ?>
</div>