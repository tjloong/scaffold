<?php

return [
    /**
     * View configurations
     */
    'view' => [
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
    ]
];