<?php

function createFlashMessage($title, $message, $icon = "fa-solid fa-bell", $type = "primary")
{
    $_SESSION['flashMessage'] = [
        'title' => $title,
        'message' => $message,
        'icon' => $icon,
        'type' => $type
    ];

    return true;
}
