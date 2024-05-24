<?php

function render($section, $template, $data = array(), $responseCode = null)
{
    extract($data);

    if (!isset($responseCode) && $section == "error") {
        $responseCode = (int) $template;
    }

    if (isset($responseCode)) {
        http_response_code($responseCode);
    }

    if (isset($section)) {
        include_once __DIR__ . "/../../templates/$section/layout/header.php";
        include_once __DIR__ . "/../../templates/$section/$template.php";
        include_once __DIR__ . "/../../templates/$section/layout/footer.php";
    } else {
        include_once __DIR__ . "/../../templates/$template.php";
    }

    exit();
}
