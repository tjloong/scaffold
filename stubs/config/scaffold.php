<?php

return [
    'web' => [
        'locales' => ['en'],
        
        'track_ref' => [
            'duration' => 7,
            'exclude_routes' => [
                'login',
                'app/*',
                'password.reset',
                'password.forgot',
            ]
        ]
    ]
];