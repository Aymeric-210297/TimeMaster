<?php

require __DIR__ . '/vendor/autoload.php';

session_start();

/**
 * Utilities
 */

// Libraries
require_once __DIR__ . "/utils/libs/router.php";

// Functions
require_once __DIR__ . "/utils/functions/checkCsrf.php";
require_once __DIR__ . "/utils/functions/logError.php";
require_once __DIR__ . "/utils/functions/render.php";
require_once __DIR__ . "/utils/functions/createErrorCallback.php";
require_once __DIR__ . "/utils/functions/redirect.php";

/**
 * Configurations
 */

// Environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// App
require_once __DIR__ . "/configs/app.php";

// Database
require_once __DIR__ . "/configs/database.php";

/**
 * Models
 */

require_once __DIR__ . "/models/BaseModel.php";

/**
 * Controllers
 */

require_once __DIR__ . "/controllers/HomeController.php";
require_once __DIR__ . "/controllers/AuthController.php";

// Handle 404 error
any('/404', function () {
    render("out", "errors/404");
});
