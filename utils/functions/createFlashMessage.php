<?php

function createFlashMessage($title, $message, $type = "primary", $icon = null)
{
    if (empty($icon)) {
        switch($type) {
            case 'error':
                $icon = "fa-solid fa-circle-exclamation";
                break;
            case 'warning':
                $icon = "fa-solid fa-triangle-exclamation";
                break;
            case 'success':
                $icon = "fa-solid fa-circle-check";
                break;
            default:
                $icon = "fa-solid fa-bell";
                break;
        }
    }

    $_SESSION['flashMessage'] = [
        'title' => $title,
        'message' => $message,
        'icon' => $icon,
        'type' => $type
    ];

    return true;
}
