<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Post;
use App\ObjectHelper;
use App\Table\PostTable;
use App\Validators\PostValidator;

$post = new Post();
$errors = [];

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $postTable = new PostTable($pdo);

    ObjectHelper::hydrate($post, $_POST);

    $v = new PostValidator($_POST, $postTable, $post->getId());
    if ($v->validate()) {
        $postTable->create($post);
        header('Location: ' . $router->url('admin_post', ['id' => $post->getId()]) . '?created=1');
        exit();
    }
    else {
        $errors = $v->getErrors();
    }
}
$form = new Form($post, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" >
        L'article n'a pas pu etre enregistré
    </div>
<?php endif ?>

<h1>Creer un nouvel article</h1>

<?php require('_form.php'); ?>