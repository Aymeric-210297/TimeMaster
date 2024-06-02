<main>
    <div class="content-header">
        <div>
            <h2>Génération des horaires</h2>
            <p>Vous pouvez générer des horaires pour votre établissement.</p>
        </div>
        <div>
            <?php if (!$school->schoolAlgoGenerating && !empty($schedules)): ?>
                <a href="/app/schools/<?= $school->schoolId ?>/schedules/generate?csrf=<?= get_csrf() ?>"
                    class="button warning">
                    Re-générer les horaires <i class="fa-solid fa-microchip"></i>
                </a>
            <?php elseif (!$school->schoolAlgoGenerating): ?>
                <a href="/app/schools/<?= $school->schoolId ?>/schedules/generate?csrf=<?= get_csrf() ?>"
                    class="button success">
                    Générer les horaires <i class="fa-solid fa-microchip"></i>
                </a>
            <?php else: ?>
                <a href="#" class="button secondary">
                    Génération en cours... <i class="fa-solid fa-microchip fa-beat-fade"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</main>
