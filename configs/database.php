<?php

try {
    $dbh = new PDO($_ENV["DATABASE_DSN"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASS"], [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $error) {
    logError($error);
    render("out", "errors/500");
}
