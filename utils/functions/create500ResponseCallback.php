<?php

function create500ResponseCallback($layout)
{
    return function () use ($layout) {
        render($layout, "errors/500");
    };
}
