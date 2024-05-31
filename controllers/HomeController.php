<?php

get('/', function () {
    if (isset($_SESSION['user'])) {
        redirect('/app');
    } else {
        redirect('/sign-in');
        // TODO: landing page
    }
});

get('/app', function () {
    if (!isset($_SESSION['user'])) {
        redirect('/sign-in');
    }

    render('app', 'home', [
        'head' => [
            'title' => 'Accueil'
        ],
        'navbarItem' => 'HOME'
    ]);
});
