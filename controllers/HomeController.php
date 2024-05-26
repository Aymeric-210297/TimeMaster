<?php

get('/', function () {
    render(
        null,
        "home",
        [
            'head' => ['title' => "Home"],
        ]
    );
});
