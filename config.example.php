<?php
// Copy to config/local.php after installation. Never commit production credentials.
return [
    'app_name' => 'Van Stal Academie',
    'base_url' => '', // e.g. /academie; leave empty at a domain root
    'timezone' => 'Europe/Amsterdam',
    'default_locale' => 'nl',
    'db' => [
        'host' => 'localhost', 'port' => 3306, 'name' => 'vanstal_lms',
        'user' => 'database_user', 'pass' => 'change-me', 'charset' => 'utf8mb4',
    ],
    'security' => ['session_name' => 'vanstal_lms', 'login_attempts' => 5, 'login_window_minutes' => 15],
];
