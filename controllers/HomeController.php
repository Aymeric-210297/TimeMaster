<?php

get('/', function () {
    render(
        "out",
        "home",
        [
            'head' => ['title' => "Home"],
        ]
    );
});
