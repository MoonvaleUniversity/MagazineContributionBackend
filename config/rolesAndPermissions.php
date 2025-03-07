<?php

return [
    // Default guard to use if not specified
    'default-guard' => 'api',

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
        'Root Admin' => [
            'permissions' => [
                'academic-year.view',
                'academic-year.create',
                'academic-year.edit',
                'academic-year.delete',
                'article.view',
                'article.create',
                'article.edit',
                'article.delete',
                'closure-date.view',
                'closure-date.create',
                'closure-date.edit',
                'closure-date.delete',
                'contribution.view',
                'contribution.create',
                'contribution.edit',
                'contribution.delete',
                'faculty.view',
                'faculty.create',
                'faculty.edit',
                'faculty.delete',
                'admin.view',
                'admin.create',
                'admin.edit',
                'admin.delete',
                'marketing-manager.view',
                'marketing-manager.create',
                'marketing-manager.edit',
                'marketing-manager.delete',
                'marketing-coordinator.view',
                'marketing-coordinator.create',
                'marketing-coordinator.edit',
                'marketing-coordinator.delete',
                'student.view',
                'student.create',
                'student.edit',
                'student.delete',
                'guest.view',
                'guest.create',
                'guest.edit',
                'guest.delete',
            ]
        ],
        'Admin' => [
            // 'guard' => 'web',  /* Custom guard if not set, default guard is used */
            'permissions' => [
                'academic-year.view',
                'academic-year.create',
                'academic-year.edit',
                'academic-year.delete',
                'closure-date.view',
                'closure-date.create',
                'closure-date.edit',
                'closure-date.delete',
                'article.view',
                'article.create',
                'article.edit',
                'article.delete',
                'contribution.view',
                'faculty.view',
                'marketing-manager.view',
                'marketing-manager.create',
                'marketing-manager.edit',
                'marketing-manager.delete'
            ]
        ],
        'Marketing Manager' => [
            'permissions' => [
                'faculty.view',
                'faculty.create',
                'faculty.edit',
                'faculty.delete',
                'article.view',
                'closure-date.view',
                'contribution.view',
                'marketing-coordinator.view',
                'marketing-coordinator.create',
                'marketing-coordinator.edit',
                'marketing-coordinator.delete'
            ]
        ],
        'Marketing Coordinator' => [
            'permissions' => [
                'closure-date.view',
                'contribution.view',
                'contribution.edit',
                'contribution.delete',
                'faculty.view',
                'student.view',
                'student.create',
                'student.edit',
                'student.delete',
                'guest.view',
                'guest.create',
                'guest.edit',
                'guest.delete',
            ]
        ],
        'Student' => [
            'permissions' => [
                'article.view',
                'closure-date.view',
                'contribution.view',
                'contribution.create',
                'contribution.edit',
                'contribution.delete',
                'faculty.view',
            ]
        ],
        'Guest' => [
            'permissions' => [
                'article.view',
                'closure-date.view',
                'contribution.view',
                'faculty.view',
            ]
        ],
    ]
];
