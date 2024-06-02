<main>
    <div class="content-header">
        <div>
            <h2>Inviter un collaborateur</h2>
            <p>Vous pouvez inviter un collaborateur via cette page.</p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "email", "Email du collaborateur", [
            'type' => 'email',
            'required' => true,
        ]) ?>

        <button type="submit" class="button primary">Inviter le collaborateur <i
                class="fa-solid fa-handshake"></i></button>
    </form>
</main>
