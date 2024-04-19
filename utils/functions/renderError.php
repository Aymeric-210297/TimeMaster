<?php

function renderError($layout, $errorCode, $message = null, $error = null)
{
    if (isset($message) && isset($error)) {
        if (!file_exists(LOGS_DIR)) {
            mkdir(LOGS_DIR);
        }

        $logMessage = "[" . date("d-m-y H:i:s") . "] ";
        $logMessage .= $message . ": " . $error->getMessage();
        $logMessage .= PHP_EOL;
        $logMessage .= $error->getTraceAsString();
        $logMessage .= PHP_EOL;
        $logMessage .= str_repeat("-", 20);
        $logMessage .= PHP_EOL;

        file_put_contents(LOGS_DIR . "/errors_" . date("d.m.y") . ".log", $logMessage, FILE_APPEND);
    }

    render($layout, "errors/" . $errorCode, [], $errorCode);
}
