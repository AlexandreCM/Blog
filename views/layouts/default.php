<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <title><?= isset($title) ? htmlentities($title) : 'Mon Site' ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a href="<?= $router->url('home') ?>" class="navbar-brand">Mon site</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['auth'])): ?>
                    <form action="<?= $router->url('logout') ?>" method="POST" style="display: inline">
                        <button type="submit" class="nav-link" style="background: transparent; border: none;">Se deconnecter</button>
                    </form>
                <?php else : ?>
                    <a href="<?= $router->url('login') ?>" class="nav-link">Se connecter</a>
                <?php endif ?>
            </li>
        </ul>
    </nav>

    <div class="container mt-4">
        <?= $content ?>
    </div>

    <footer class="bg-light py-4 footer mt-auto">
        <div class="container">
            <?php if (defined('DEBUG_TIME')): ?>
            Page générée en <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?>ms
            <?php endif ?>
        </div>
    </footer>
</body>
</html>
