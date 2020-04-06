<?php

use App\Auth;
use App\Connection;
use App\Model\Post;
use App\Table\PostTable;

Auth::check();

$title = "Administration";
$link = $router->url('admin_posts');

$pdo = Connection::getPDO();
/**
 * @var Post[] $posts
 * @var string $pagination
 */
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

?>

<?php if (isset($_GET['delete'])): ?>
    <div class="alert alert-success" >
        L'enregistrement a bien été supprimé
    </div>
<?php endif ?>

<table class="table">
    <thead>
        <th>Titre</th>
        <th>Actions</th>
    </thead>
    <tbody>
<?php foreach ($posts as $post): ?>
        <tr>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>">
                    <?= htmlentities($post->getName()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <form action="<?= $router->url('admin_post_delete', ['id' => $post->getId()]) ?>" method="POST"
                    onsubmit="return confirm('Supprimer ??')" style="display: inline">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
<?php endforeach ?>
    </tbody>
</table>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousPage($link); ?>
    <?= $pagination->nextPage($link); ?>
</div>
