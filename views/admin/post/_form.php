<form action="" method="POST">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'URL'); ?>
    <?= $form->textarea('content', 'Contenu'); ?>
    <?= $form->input('created_at', 'Date'); ?>
    <button class="btn btn-primary">
        <?php if ($post->getId() !== null): ?>
            Modifier
        <?php else : ?>
            Creer
        <?php endif ?>
    </button>
</form>