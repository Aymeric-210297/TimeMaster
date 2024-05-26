<?php

get('/', function () {
    if (isset ($_SESSION['user'])) {
        redirect('/app');
    } else {
        redirect('/sign-in');
        // TODO: landing page
    }
});

get('/app', function () {
    render('app', 'home', [
        'head' => [
            'title' => 'App home'
        ],
        'navbarItem' => 'HOME'
    ]);
});
