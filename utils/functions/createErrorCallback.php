<?php

function createErrorCallback($responseCode, $layout)
{
    return function () use ($responseCode, $layout) {
        render($layout, "errors/" . $responseCode);
    };
}
