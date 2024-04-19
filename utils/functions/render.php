<?php

function render($layout, $template, $data = array(), $responseCode = 0)
{
    extract($data);

    http_response_code($responseCode);

    include_once __DIR__ . "/../../templates/layouts/$layout/header.php";
    include_once __DIR__ . "/../../templates/$template.php";
    include_once __DIR__ . "/../../templates/layouts/$layout/footer.php";

    exit();
}
