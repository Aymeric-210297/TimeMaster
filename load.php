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
require_once __DIR__ . "/utils/functions/checkAuth.php";
require_once __DIR__ . "/utils/functions/logError.php";
require_once __DIR__ . "/utils/functions/render.php";
require_once __DIR__ . "/utils/functions/createErrorCallback.php";
require_once __DIR__ . "/utils/functions/redirect.php";
require_once __DIR__ . "/utils/functions/refreshSession.php";
require_once __DIR__ . "/utils/functions/createFlashMessage.php";
require_once __DIR__ . "/utils/functions/validateData.php";

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

// TODO: Ajouter les head['title'] dans les routes
require_once __DIR__ . "/controllers/HomeController.php";
require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/SchoolController.php";
require_once __DIR__ . "/controllers/StudentController.php";
require_once __DIR__ . "/controllers/TeacherController.php";
require_once __DIR__ . "/controllers/ClassController.php";
require_once __DIR__ . "/controllers/ClassroomController.php";
require_once __DIR__ . "/controllers/SubjectController.php";
require_once __DIR__ . "/controllers/CollaboratorController.php";

// Handle 404 error
any('/404', function () {
    render("error", "404");
});
