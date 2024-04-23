<?php

function checkCsrf()
{
    if (is_csrf_valid()) {
        return;
    }

    render("out", "errors/403");
}
