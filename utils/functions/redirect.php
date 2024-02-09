<?php

function redirect($location = null, $responseCode = 0)
{
    if (empty($location)) {
        $location = $_SERVER["HTTP_REFERER"];
    }

    header("Location: $location", true, $responseCode);

    exit();
}
