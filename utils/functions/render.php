<?php

function render($layout, $template, $data = array(), $responseCode = null)
{
    extract($data);

    if (!isset($responseCode) && str_starts_with($template, "errors/")) {
        $responseCode = (int) explode("errors/", $template)[1];
    }

    if (isset($responseCode)) {
        http_response_code($responseCode);
    }

    include_once __DIR__ . "/../../templates/layouts/$layout/header.php";
    include_once __DIR__ . "/../../templates/$template.php";
    include_once __DIR__ . "/../../templates/layouts/$layout/footer.php";

    exit();
}
