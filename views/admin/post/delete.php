<?php

use App\Connection;
use App\Table\PostTable;

?>
    <h1>Delete <?= $params['id'] ?></h1>

<?php
$title = "Administration";

$pdo = Connection::getPDO();
$table = new PostTable($pdo);
//$table->delete($params['id']);
header('Location: ' . $router->url('admin_posts') . '?delete=1');