<?php

function refreshSession($userModel)
{
    $user = $userModel->getUserById($_SESSION['user']->userId);
    $_SESSION['user'] = $user;
    return true;
}
