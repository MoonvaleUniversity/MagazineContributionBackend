<?php

return [
    // Default guard to use if not specified
    'default-guard' => 'web',

    // Common actions (prevents repetition)
    'common-actions' => ['view', 'create', 'edit', 'delete'],

    //Permissions and Actions
    'permissions' => [
        'academic-year' => [
            // 'guard' => 'api', /* Custom guard if not set, default guard is used */
            'common-actions' => true,
            'actions' => [],
        ],

        'article' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'closure-date' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'contribution' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'faculty' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'admin' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'marketing-manager' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'marketing-coordinator' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'student' => [
            'common-actions' => true,
            'actions' => [],
        ],

        'guest' => [
            'common-actions' => true,
            'actions' => [],
        ],

    ],

    //Roles and Permissions
    'roles' => [
        'Admin' => [
            // 'guard' => 'web',  /* Custom guard if not set, default guard is used */
            'permissions' => []
        ],
        'Marketing Manager' => [
            'permissions' => []
        ],
        'Marketing Coordinator' => [
            'permissions' => []
        ],
        'Student' => [
            'permissions' => []
        ],
        'Guest' => [
            'permissions' => []
        ],
    ]
];
