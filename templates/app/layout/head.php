<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= !empty($head["title"]) ? $head["title"] . " | " . APP_NAME : APP_NAME ?></title>

    <link rel="stylesheet" href="/assets/styles/index.css">
    <link rel="stylesheet" href="/assets/styles/sidebar.css">
    <link rel="stylesheet" href="/assets/styles/form.css">

    <script src="<?= $_ENV["FONT_AWESOME_KIT_URL"] ?>" crossorigin="anonymous"></script>
</head>
