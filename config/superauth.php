<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SuperAuth Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the SuperAuth package.
    | You can customize these settings according to your application needs.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Features Configuration
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific features of the SuperAuth package.
    |
    */
    'features' => [
        'authentication' => [
            'enabled' => env('SUPERAUTH_AUTHENTICATION_ENABLED', true),
            'methods' => [
                'traditional' => env('SUPERAUTH_AUTH_TRADITIONAL_ENABLED', true),
                'social' => env('SUPERAUTH_AUTH_SOCIAL_ENABLED', true),
                'otp' => env('SUPERAUTH_AUTH_OTP_ENABLED', false),
            ],
            'email_verification' => env('SUPERAUTH_EMAIL_VERIFICATION_ENABLED', true),
            'password_reset' => env('SUPERAUTH_PASSWORD_RESET_ENABLED', true),
        ],
        'authorization' => [
            'enabled' => env('SUPERAUTH_AUTHORIZATION_ENABLED', true),
            'roles' => [
                'default_user_role' => env('SUPERAUTH_DEFAULT_USER_ROLE', 'user'),
                'default_admin_role' => env('SUPERAUTH_DEFAULT_ADMIN_ROLE', 'admin'),
            ],
        ],
        'security' => [
            'enabled' => env('SUPERAUTH_SECURITY_ENABLED', true),
            'password_breach_checking' => env('SUPERAUTH_BREACH_CHECK_ENABLED', true),
            'password_strength_analysis' => env('SUPERAUTH_STRENGTH_ANALYSIS_ENABLED', true),
        ],
        'notifications' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_ENABLED', true),
            'channels' => [
                'email' => env('SUPERAUTH_NOTIFICATIONS_EMAIL_ENABLED', true),
                'telegram' => env('SUPERAUTH_NOTIFICATIONS_TELEGRAM_ENABLED', false),
                'slack' => env('SUPERAUTH_NOTIFICATIONS_SLACK_ENABLED', false),
                'whatsapp' => env('SUPERAUTH_NOTIFICATIONS_WHATSAPP_ENABLED', false),
                'sms' => env('SUPERAUTH_NOTIFICATIONS_SMS_ENABLED', false),
            ],
        ],
        'ai_agent' => [
            'enabled' => env('SUPERAUTH_AI_AGENT_ENABLED', true),
            'realtime_monitoring' => env('SUPERAUTH_AI_REALTIME_MONITORING', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Authentication
    |--------------------------------------------------------------------------
    |
    | Configuration for social authentication providers.
    |
    */
    'social' => [
        'providers' => [
            'google' => [
                'enabled' => env('SUPERAUTH_GOOGLE_ENABLED', true),
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            ],
            'facebook' => [
                'enabled' => env('SUPERAUTH_FACEBOOK_ENABLED', true),
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            ],
            'github' => [
                'enabled' => env('SUPERAUTH_GITHUB_ENABLED', true),
                'client_id' => env('GITHUB_CLIENT_ID'),
                'client_secret' => env('GITHUB_CLIENT_SECRET'),
            ],
            'apple' => [
                'enabled' => env('SUPERAUTH_APPLE_ENABLED', true),
                'client_id' => env('APPLE_CLIENT_ID'),
                'client_secret' => env('APPLE_CLIENT_SECRET'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    |
    | Configuration for password security features.
    |
    */
    'password' => [
        'breach_check' => [
            'enabled' => env('SUPERAUTH_BREACH_CHECK_ENABLED', true),
            'api_url' => 'https://api.pwnedpasswords.com/range/',
            'timeout' => 10,
            'cache_ttl' => 3600, // 1 hour
        ],
        'strength' => [
            'enabled' => env('SUPERAUTH_STRENGTH_ANALYSIS_ENABLED', true),
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OTP (One-Time Password) authentication.
    |
    */
    'otp' => [
        'enabled' => env('SUPERAUTH_OTP_ENABLED', false),
        'length' => 6,
        'expiry' => 300, // 5 minutes
        'attempts' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | Configuration for notification channels.
    |
    */
    'notifications' => [
        'email' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_EMAIL_ENABLED', true),
            'from_address' => env('SUPERAUTH_EMAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            'from_name' => env('SUPERAUTH_EMAIL_FROM_NAME', env('MAIL_FROM_NAME')),
        ],
        'telegram' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_TELEGRAM_ENABLED', false),
            'bot_token' => env('SUPERAUTH_TELEGRAM_BOT_TOKEN'),
            'chat_id' => env('SUPERAUTH_TELEGRAM_CHAT_ID'),
        ],
        'slack' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_SLACK_ENABLED', false),
            'webhook_url' => env('SUPERAUTH_SLACK_WEBHOOK_URL'),
        ],
        'whatsapp' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_WHATSAPP_ENABLED', false),
            'api_url' => env('SUPERAUTH_WHATSAPP_API_URL'),
            'api_token' => env('SUPERAUTH_WHATSAPP_API_TOKEN'),
        ],
        'sms' => [
            'enabled' => env('SUPERAUTH_NOTIFICATIONS_SMS_ENABLED', false),
            'provider' => env('SUPERAUTH_SMS_PROVIDER', 'twilio'),
            'twilio' => [
                'account_sid' => env('SUPERAUTH_TWILIO_ACCOUNT_SID'),
                'auth_token' => env('SUPERAUTH_TWILIO_AUTH_TOKEN'),
                'from_number' => env('SUPERAUTH_TWILIO_FROM_NUMBER'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Agent Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI Agent features.
    |
    */
    'ai_agent' => [
        'enabled' => env('SUPERAUTH_AI_AGENT_ENABLED', true),
        'realtime_monitoring' => env('SUPERAUTH_AI_REALTIME_MONITORING', true),
        'anomaly_detection' => env('SUPERAUTH_AI_ANOMALY_DETECTION', true),
        'auto_alerting' => env('SUPERAUTH_AI_AUTO_ALERTING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for theme and UI customization.
    |
    */
    'theme' => [
        'default' => env('SUPERAUTH_DEFAULT_THEME', 'light'),
        'available_themes' => ['light', 'dark', 'auto'],
        'persist_theme' => env('SUPERAUTH_PERSIST_THEME', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for route customization.
    |
    */
    'routes' => [
        'prefix' => env('SUPERAUTH_ROUTES_PREFIX', ''),
        'middleware' => ['web'],
        'auth_middleware' => ['web', 'auth'],
        'admin_middleware' => ['web', 'auth', 'role:admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | View Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for view customization.
    |
    */
    'views' => [
        'namespace' => 'superauth',
        'theme' => env('SUPERAUTH_VIEW_THEME', 'default'),
        'layout' => env('SUPERAUTH_LAYOUT', 'superauth::layouts.app'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for caching.
    |
    */
    'cache' => [
        'enabled' => env('SUPERAUTH_CACHE_ENABLED', true),
        'prefix' => 'superauth',
        'ttl' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for logging.
    |
    */
    'logging' => [
        'enabled' => env('SUPERAUTH_LOGGING_ENABLED', true),
        'level' => env('SUPERAUTH_LOG_LEVEL', 'info'),
        'channel' => env('SUPERAUTH_LOG_CHANNEL', 'daily'),
    ],
];