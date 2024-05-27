<?php if (isset($_SESSION['flashMessage'])): ?>
    <div id="flash-message" class="<?= out($_SESSION['flashMessage']['type']) ?>">
        <div>
            <i class="icon <?= out($_SESSION['flashMessage']['icon']) ?>"></i>
            <div>
                <p class="title"><?= out($_SESSION['flashMessage']['title']) ?></p>
                <p class="message"><?= out($_SESSION['flashMessage']['message']) ?></p>
            </div>
        </div>
        <button id="close-flash-message" class="icon-button primary"><i class="fa-solid fa-close"></i></button>
    </div>
    <?php unset($_SESSION['flashMessage']); ?>
<?php endif; ?>
