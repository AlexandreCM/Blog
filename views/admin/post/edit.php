<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\Table\PostTable;
use App\Validator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
/** @var Post $post */
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)) {
    $post->setName($_POST['name']);

    Validator::lang('fr');
    $v = new Validator($_POST);
    $v->rule('required', ['name', 'slug']);
    $v->rule('lengthBetween', ['name', 'slug'], 3, 250);

    if ($v->validate()) {
        $postTable->update($post);
        $success = true;
    }
    else {
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        L'article a bien été modifié
    </div>
<?php endif ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" >
        L'article n'a pas pu etre modifié
    </div>
<?php endif ?>

<h1>Editon de <?= $post->getName() ?></h1>

<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'URL'); ?>
    <?= $form->textarea('content', 'Contenu'); ?>
    <?= $form->input('created_at', 'Date'); ?>
    <button class="btn btn-primary">Modifier</button>
</form>