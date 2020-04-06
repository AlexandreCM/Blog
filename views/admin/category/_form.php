<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'URL'); ?>
    <button class="btn btn-primary">
        <?php if ($category->getId() !== null): ?>
            Modifier
        <?php else : ?>
            Creer
        <?php endif ?>
    </button>
</form>