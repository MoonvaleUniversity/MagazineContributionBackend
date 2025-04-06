<?php

return [
    // Database Connection
    'database' => [
        'default' => env('DB_CONNECTION', 'mysql:write'),
        'write' => 'mysql:write',
        'read' => 'mysql:read'
    ],

    // User Roles
    'roles' => [
        'admin' => 'Admin',
        'marketing_manager' => 'Marketing Manager',
        'marketing_coordinator' => 'Marketing Coordinator',
        'student' => 'Student',
        'guest' => 'Guest',
    ],

    // Other system-wide constants
    'pagPerPage' => 5
];
