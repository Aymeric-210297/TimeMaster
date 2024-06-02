<?php

function getNewPaginationUrl($page)
{
    $url = parse_url($_SERVER['REQUEST_URI']);
    parse_str($url['query'] ?? '', $queryParameters);
    $queryParameters['page'] = $page;
    $newQueryString = http_build_query($queryParameters);
    return $url['path'] . '?' . $newQueryString;
}

function getPaginationLink($page, $currentPage)
{
    $class = $page == $currentPage ? 'active' : '';

    $newUrl = getNewPaginationUrl($page);

    return <<<HTML
        <a href="$newUrl" class="$class">$page</a>
    HTML;
}

?>

<?php if (isset($pageCount) && $pageCount > 1): ?>
    <div class="pagination">
        <a href="<?= $page === 1 ? '#' : getNewPaginationUrl($page - 1) ?>"
            class="left<?= $page === 1 ? ' disabled' : '' ?>">
            <i class="fa-solid fa-arrow-left"></i>
            Page précédente
        </a>

        <div>
            <?= getPaginationLink(1, $page) ?>

            <?php if ($page > 3): ?>
                <span>...</span>
            <?php endif; ?>

            <?php for ($i = max(2, $page - 1); $i <= min($page + 1, $pageCount - 1); $i++): ?>
                <?= getPaginationLink($i, $page) ?>
            <?php endfor; ?>

            <?php if ($page < $pageCount - 2): ?>
                <span>...</span>
            <?php endif; ?>

            <?= getPaginationLink($pageCount, $page) ?>
        </div>

        <a href="<?= $page >= $pageCount ? '#' : getNewPaginationUrl($page + 1) ?>"
            class="right<?= $page >= $pageCount ? ' disabled' : '' ?>">
            Page suivante
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
<?php endif; ?>
