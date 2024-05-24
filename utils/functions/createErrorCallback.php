<?php

function createErrorCallback($responseCode)
{
    return function () use ($responseCode) {
        render("error", $responseCode);
    };
}
