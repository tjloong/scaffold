<?php

return [
    'web' => [
        'locales' => ['en'],
        
        'track_ref' => [
            'duration' => 7,
            'exclude_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
        ],

        /**
         * Metatags
         */
        'metatags' => [
            'title' => '',
            'description' => '',
            'image' => '',
            'facebook_domain_verification' => '',
            'exclude_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
            'noindex' => false,
            'noindex_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
            'hreflang' => [
                // 'path/to/url' => 'zh-my',
            ],
            'canonical' => [
                // 'path/to/url' => 'canonical/url',
            ],
        ],

        /**
         * Gtm
         */
        'gtm' => [
            'id' => '',
            'exclude_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
        ],

        /**
         * FB Pixel
         */
        'fbpixel' => [
            'id' => '',
            'exclude_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
        ],

        /**
         * Google Analytics
         */
        'ga' => [
            'id' => '',
            'exclude_paths' => [
                'app/*',
                'login',
                'forgot-password',
                'reset-password',
            ],
        ],
    ]
];