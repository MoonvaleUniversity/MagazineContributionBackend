<?php

return [
    'guards' => [
        'academic-year' => [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'article' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'closure-date' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'contribution' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'faculty' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'admin' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'marketing-manager' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'marketing-coordinator' =>
        [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'student' => [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
        'guest' => [
            'actions' => [
                'view',
                'create',
                'edit',
                'delete'
            ]
        ],
    ],
    'roles' => [
        'admin' => [
            'permissions' => []
        ],
        'marketing-manager' => [
            'permissions' => []
        ],
        'marketing-coordinator' => [
            'permissions' => []
        ],
        'student' => [
            'permissions' => []
        ],
        'guest' => [
            'permissions' => []
        ],
    ]
];
