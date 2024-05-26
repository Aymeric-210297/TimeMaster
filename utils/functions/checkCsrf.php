<?php

function checkCsrf()
{
    if (is_csrf_valid()) {
        return;
    }

    render("error", "403");
}
