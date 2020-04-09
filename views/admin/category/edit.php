<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$pdo = Connection::getPDO();
$categoryTable = new CategoryTable($pdo);
/** @var Category $category */
$category = $categoryTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)) {

    ObjectHelper::hydrate($category, $_POST);

    $v = new CategoryValidator($_POST, $categoryTable, $category->getId());
    if ($v->validate()) {
        $categoryTable->update($category);
        $success = true;
    }
    else {
        $errors = $v->getErrors();
    }
}
$form = new Form($category, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        La categorie a bien été modifié
    </div>
<?php endif ?>
<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        La categorie a bien été creer
    </div>
<?php endif ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" >
        La categorie n'a pas pu etre modifié
    </div>
<?php endif ?>

<h1>Editon de <?= $category->getName() ?></h1>

<?php require('_form.php'); ?>