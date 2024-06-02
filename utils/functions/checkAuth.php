<?php

function checkAuth($userModel = null, $schoolId = null)
{
    if (!isset($_SESSION['user'])) {
        redirect('/sign-in');
    }

    if (!empty($schoolId)) {
        $schools = $userModel->getUserSchools($_SESSION['user']->userId);
        if (!in_array($schoolId, $schools)) {
            render('error', '403');
        }
    }
}
