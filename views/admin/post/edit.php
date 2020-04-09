<?php

use App\Auth;
use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
/** @var Post $post */
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)) {

    ObjectHelper::hydrate($post, $_POST);

    $v = new PostValidator($_POST, $postTable, $post->getId());
    if ($v->validate()) {
        $postTable->update($post);
        $success = true;
    }
    else {
        $errors = $v->getErrors();
    }
}
$form = new Form($post, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        L'article a bien été modifié
    </div>
<?php endif ?>
<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        L'article a bien été creer
    </div>
<?php endif ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" >
        L'article n'a pas pu etre modifié
    </div>
<?php endif ?>

<h1>Editon de <?= $post->getName() ?></h1>

<?php require('_form.php'); ?>