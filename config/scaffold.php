<?php

return [
    /**
     * Website configurations
     */
    'web' => [
        'locales' => ['en'],

        /**
         * Track referral
         */
        'track_ref' => [
            'duration' => 0,
            'exclude_routes' => [
                'app/*',
            ],
        ],

        /**
         * App logo
         */
        'logo' => 'logo.svg',
        'logo_error' => 'logo-e.svg',
        'favicon' => 'logo.svg',
        'blob_bg' => 'blob-bg.svg',

        /**
         * Metatags configuration
         */
        'metatags' => [
            'title' => null,
            'description' => null,
            'image' => null,
            'noindex' => false,
            'facebook_domain_verification' => null,
            'exclude_routes' => [
                'login',
                'verification.verify',
                'password.forgot',
                'password.reset',
            ],
            'noindex_routes' => [
                //
            ],
            'hreflang' => [
                //
            ],
            'canonical' => [
                //
            ],
        ],

        /**
         * Google tag manager configuration
         */
        'gtm' => [
            'id' => null,
            'exclude_routes' => [
                'login',
                'verification.verify',
                'password.forgot',
                'password.reset',
            ],
        ],

        /**
         * Google Analytics configuration
         */
        'ga' => [
            'id' => null,
            'exclude_routes' => [
                'login',
                'verification.verify',
                'password.forgot',
                'password.reset',
            ],
        ],

        /**
         * Facebook pixel configuration
         */
        'fbpixel' => [
            'id' => null,
            'exclude_routes' => [
                'login',
                'verification.verify',
                'password.forgot',
                'password.reset',
            ],
        ],
    ],
];