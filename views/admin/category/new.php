<?php

use App\Connection;
use App\HTML\Form;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

$category = new Category();
$errors = [];

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $categoryTable = new CategoryTable($pdo);

    ObjectHelper::hydrate($category, $_POST);

    $v = new CategoryValidator($_POST, $categoryTable, $category->getId());
    if ($v->validate()) {
        $categoryTable->create($category);
        header('Location: ' . $router->url('admin_category', ['id' => $category->getId()]) . '?created=1');
        exit();
    }
    else {
        $errors = $v->getErrors();
    }
}
$form = new Form($category, $errors);
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" >
        La categorie n'a pas pu etre enregistré
    </div>
<?php endif ?>

<h1>Creer une nouvelle categorie</h1>

<?php require('_form.php'); ?>