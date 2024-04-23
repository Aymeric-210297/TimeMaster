<?php

get('/sign-in', function () {
    render(
        "auth",
        "auth/sign-in",
        [
            'head' => ['title' => "Connexion"],
        ]
    );
});

get('/sign-up', function () {
    render(
        "auth",
        "auth/sign-up",
        [
            'head' => ['title' => "Inscription"],
        ]
    );
});
