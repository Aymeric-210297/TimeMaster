<main>
    <div class="content-header">
        <div>
            <h2><?= isset($class) ? 'Modifier' : 'Ajouter' ?> une classe</h2>
            <p><?= isset($class) ? 'Vous êtes en train de modifier une classe existante.' : 'Vous pouvez ajouter une classe via cette page.' ?>
            </p>
        </div>
        <?php if (isset($class)): ?>
            <div>
                <a href="/app/schools/<?= $school->schoolId ?>/classes/<?= $class->classId ?>/students"
                    class="button primary">
                    Modifier les élèves de la classe <i class="fa-solid fa-users-line"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "ref", "Nom de la classe", [
            'type' => 'text',
            'required' => true,
            'value' => isset($class) ? $class->classRef : null
        ]) ?>

        <button type="submit" class="button primary"><?= isset($class) ? 'Sauvegarder' : 'Ajouter' ?>
            la classe <i class="fa-solid fa-<?= isset($class) ? 'save' : 'add' ?>"></i></button>
        <?php if (isset($class)): ?>
            <a href="/app/schools/<?= $school->schoolId ?>/classes/<?= $class->classId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>"
                class="button error">Retirer la classe <i class="fa-solid fa-trash"></i></a>
        <?php endif; ?>
    </form>
</main>
