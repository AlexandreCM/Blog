<?php

use App\Auth;
use App\Connection;
use App\Model\Category;
use App\Table\CategoryTable;

Auth::check();

$title = "Categories";
$link = $router->url('admin_category');

$pdo = Connection::getPDO();
/** @var Category[] $categories $categories */
$categories = (new CategoryTable($pdo))->findAll();

?>

<?php if (isset($_GET['delete'])): ?>
    <div class="alert alert-success" >
        L'enregistrement a bien été supprimé
    </div>
<?php endif ?>

<table class="table">
    <thead>
        <th>Titre</th>
        <th>URL</th>
        <th>
            <a href="<?= $router->url('admin_category_new') ?>" class="btn btn-primary">Nouveau</a>
        </th>
    </thead>
    <tbody>
<?php foreach ($categories as $category): ?>
        <tr>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $category->getId()]) ?>">
                    <?= htmlentities($category->getName()) ?>
                </a>
            </td>
            <td><?= $category->getSlug() ?></td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $category->getId()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <form action="<?= $router->url('admin_category_delete', ['id' => $category->getId()]) ?>" method="POST"
                      onsubmit="return confirm('Supprimer ??')" style="display: inline">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
<?php endforeach ?>
    </tbody>
</table>
