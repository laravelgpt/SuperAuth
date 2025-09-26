<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Feature Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Authentication
    | feature of the SuperAuth package.
    |
    */

    'enabled' => env('SUPERAUTH_AUTH_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Authentication Methods
    |--------------------------------------------------------------------------
    */
    'methods' => [
        'email_password' => [
            'enabled' => env('SUPERAUTH_EMAIL_PASSWORD_ENABLED', true),
            'min_password_length' => env('SUPERAUTH_MIN_PASSWORD_LENGTH', 8),
            'require_uppercase' => env('SUPERAUTH_REQUIRE_UPPERCASE', true),
            'require_lowercase' => env('SUPERAUTH_REQUIRE_LOWERCASE', true),
            'require_numbers' => env('SUPERAUTH_REQUIRE_NUMBERS', true),
            'require_symbols' => env('SUPERAUTH_REQUIRE_SYMBOLS', true),
        ],
        'otp' => [
            'enabled' => env('SUPERAUTH_OTP_ENABLED', true),
            'length' => env('SUPERAUTH_OTP_LENGTH', 6),
            'expiry_minutes' => env('SUPERAUTH_OTP_EXPIRY_MINUTES', 10),
            'max_attempts' => env('SUPERAUTH_OTP_MAX_ATTEMPTS', 3),
            'decay_minutes' => env('SUPERAUTH_OTP_DECAY_MINUTES', 15),
        ],
        'social' => [
            'enabled' => env('SUPERAUTH_SOCIAL_ENABLED', true),
            'providers' => [
                'google' => [
                    'enabled' => env('SUPERAUTH_GOOGLE_ENABLED', true),
                    'client_id' => env('GOOGLE_CLIENT_ID'),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                    'redirect' => env('GOOGLE_REDIRECT_URI'),
                ],
                'facebook' => [
                    'enabled' => env('SUPERAUTH_FACEBOOK_ENABLED', true),
                    'client_id' => env('FACEBOOK_CLIENT_ID'),
                    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                    'redirect' => env('FACEBOOK_REDIRECT_URI'),
                ],
                'github' => [
                    'enabled' => env('SUPERAUTH_GITHUB_ENABLED', true),
                    'client_id' => env('GITHUB_CLIENT_ID'),
                    'client_secret' => env('GITHUB_CLIENT_SECRET'),
                    'redirect' => env('GITHUB_REDIRECT_URI'),
                ],
                'apple' => [
                    'enabled' => env('SUPERAUTH_APPLE_ENABLED', true),
                    'client_id' => env('APPLE_CLIENT_ID'),
                    'client_secret' => env('APPLE_CLIENT_SECRET'),
                    'redirect' => env('APPLE_REDIRECT_URI'),
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'account_lockout' => [
            'enabled' => env('SUPERAUTH_ACCOUNT_LOCKOUT_ENABLED', true),
            'max_attempts' => env('SUPERAUTH_ACCOUNT_LOCKOUT_ATTEMPTS', 5),
            'lockout_duration' => env('SUPERAUTH_ACCOUNT_LOCKOUT_DURATION', 30), // minutes
        ],
        'password_breach_check' => [
            'enabled' => env('SUPERAUTH_PASSWORD_BREACH_CHECK_ENABLED', true),
            'api_url' => env('SUPERAUTH_BREACH_CHECK_API_URL', 'https://api.pwnedpasswords.com/range/'),
            'timeout' => env('SUPERAUTH_BREACH_CHECK_TIMEOUT', 10),
            'cache_ttl' => env('SUPERAUTH_BREACH_CHECK_CACHE_TTL', 3600),
        ],
        'password_strength' => [
            'enabled' => env('SUPERAUTH_PASSWORD_STRENGTH_ENABLED', true),
            'min_score' => env('SUPERAUTH_PASSWORD_STRENGTH_MIN_SCORE', 60),
            'real_time_validation' => env('SUPERAUTH_PASSWORD_STRENGTH_REAL_TIME', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'theme' => env('SUPERAUTH_AUTH_THEME', 'glass-morphism'),
        'dark_mode' => env('SUPERAUTH_AUTH_DARK_MODE', true),
        'animations' => env('SUPERAUTH_AUTH_ANIMATIONS', true),
        'responsive' => env('SUPERAUTH_AUTH_RESPONSIVE', true),
        'accessibility' => env('SUPERAUTH_AUTH_ACCESSIBILITY', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => env('SUPERAUTH_AUTH_ROUTE_PREFIX', 'auth'),
        'middleware' => ['web', 'guest'],
        'login' => [
            'enabled' => true,
            'path' => '/login',
            'name' => 'superauth.auth.login',
        ],
        'register' => [
            'enabled' => true,
            'path' => '/register',
            'name' => 'superauth.auth.register',
        ],
        'forgot_password' => [
            'enabled' => true,
            'path' => '/forgot-password',
            'name' => 'superauth.auth.forgot-password',
        ],
        'reset_password' => [
            'enabled' => true,
            'path' => '/reset-password',
            'name' => 'superauth.auth.reset-password',
        ],
        'otp' => [
            'enabled' => true,
            'path' => '/otp',
            'name' => 'superauth.auth.otp',
        ],
        'social' => [
            'enabled' => true,
            'path' => '/social',
            'name' => 'superauth.auth.social',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | View Settings
    |--------------------------------------------------------------------------
    */
    'views' => [
        'layout' => 'superauth::shared.layouts.auth',
        'login' => 'superauth::features.authentication.login',
        'register' => 'superauth::features.authentication.register',
        'forgot_password' => 'superauth::features.authentication.forgot-password',
        'reset_password' => 'superauth::features.authentication.reset-password',
        'otp' => 'superauth::features.authentication.otp',
        'social' => 'superauth::features.authentication.social',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Settings
    |--------------------------------------------------------------------------
    */
    'events' => [
        'login_success' => 'SuperAuth\Events\Authentication\LoginSuccess',
        'login_failed' => 'SuperAuth\Events\Authentication\LoginFailed',
        'register_success' => 'SuperAuth\Events\Authentication\RegisterSuccess',
        'password_reset' => 'SuperAuth\Events\Authentication\PasswordReset',
        'otp_sent' => 'SuperAuth\Events\Authentication\OtpSent',
        'otp_verified' => 'SuperAuth\Events\Authentication\OtpVerified',
        'social_login' => 'SuperAuth\Events\Authentication\SocialLogin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('SUPERAUTH_AUTH_CACHE_ENABLED', true),
        'ttl' => env('SUPERAUTH_AUTH_CACHE_TTL', 3600),
        'prefix' => 'superauth:auth:',
    ],
];
