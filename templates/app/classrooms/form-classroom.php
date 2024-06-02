<main>
    <div class="content-header">
        <div>
            <h2><?= isset($classroom) ? 'Modifier' : 'Ajouter' ?> une salle de classe</h2>
            <p><?= isset($classroom) ? 'Vous êtes en train de modifier une salle de classe existant.' : 'Vous pouvez ajouter une salle de classe via cette page.' ?>
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "ref", "Nom de la salle de classe", [
            'type' => 'text',
            'required' => true,
            'value' => isset($classroom) ? $classroom->classroomRef : null
        ]) ?>
        <?= getFormInput($formViolations, "nbPlace", "Nombre de places", [
            'type' => 'number',
            'required' => true,
            'value' => isset($classroom) ? $classroom->classroomNumberSeats : null
        ]) ?>
        <!-- TODO: utiliser un switch on/off -->
        <?= getFormInput($formViolations, "projector", "La classe possède un projecteur", [
            'type' => 'number',
            'required' => true,
            'value' => isset($classroom) ? $classroom->classroomProjector : null
        ], "Indiquez 0 pour non et 1 pour oui") ?>

        <button type="submit" class="button primary"><?= isset($classroom) ? 'Sauvegarder' : 'Ajouter' ?> la salle de
            classe <i class="fa-solid fa-<?= isset($classroom) ? 'save' : 'add' ?>"></i></button>
        <?php if (isset($classroom)): ?>
            <a href="/app/schools/<?= $school->schoolId ?>/classrooms/<?= $classroom->classroomId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>"
                class="button error">Retirer la salle de classe <i class="fa-solid fa-trash"></i></a>
        <?php endif; ?>
    </form>
</main>
