<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Feature Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Security
    | feature of the SuperAuth package.
    |
    */

    'enabled' => env('SUPERAUTH_SECURITY_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    */
    'password' => [
        'breach_check' => [
            'enabled' => env('SUPERAUTH_PASSWORD_BREACH_CHECK_ENABLED', true),
            'api_url' => env('SUPERAUTH_BREACH_CHECK_API_URL', 'https://api.pwnedpasswords.com/range/'),
            'timeout' => env('SUPERAUTH_BREACH_CHECK_TIMEOUT', 10),
            'cache_ttl' => env('SUPERAUTH_BREACH_CHECK_CACHE_TTL', 3600),
            'rate_limit' => env('SUPERAUTH_BREACH_CHECK_RATE_LIMIT', 100),
        ],
        'strength' => [
            'enabled' => env('SUPERAUTH_PASSWORD_STRENGTH_ENABLED', true),
            'min_score' => env('SUPERAUTH_PASSWORD_STRENGTH_MIN_SCORE', 60),
            'real_time_validation' => env('SUPERAUTH_PASSWORD_STRENGTH_REAL_TIME', true),
            'requirements' => [
                'min_length' => env('SUPERAUTH_PASSWORD_MIN_LENGTH', 8),
                'require_uppercase' => env('SUPERAUTH_PASSWORD_REQUIRE_UPPERCASE', true),
                'require_lowercase' => env('SUPERAUTH_PASSWORD_REQUIRE_LOWERCASE', true),
                'require_numbers' => env('SUPERAUTH_PASSWORD_REQUIRE_NUMBERS', true),
                'require_symbols' => env('SUPERAUTH_PASSWORD_REQUIRE_SYMBOLS', true),
                'min_unique_chars' => env('SUPERAUTH_PASSWORD_MIN_UNIQUE_CHARS', 8),
            ],
        ],
        'history' => [
            'enabled' => env('SUPERAUTH_PASSWORD_HISTORY_ENABLED', true),
            'max_history' => env('SUPERAUTH_PASSWORD_MAX_HISTORY', 5),
            'prevent_reuse' => env('SUPERAUTH_PASSWORD_PREVENT_REUSE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'enabled' => env('SUPERAUTH_SECURITY_HEADERS_ENABLED', true),
        'x_content_type_options' => env('SUPERAUTH_X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'x_frame_options' => env('SUPERAUTH_X_FRAME_OPTIONS', 'DENY'),
        'x_xss_protection' => env('SUPERAUTH_X_XSS_PROTECTION', '1; mode=block'),
        'referrer_policy' => env('SUPERAUTH_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'permissions_policy' => env('SUPERAUTH_PERMISSIONS_POLICY', 'geolocation=(), microphone=(), camera=()'),
        'strict_transport_security' => env('SUPERAUTH_STRICT_TRANSPORT_SECURITY', 'max-age=31536000; includeSubDomains'),
        'content_security_policy' => [
            'enabled' => env('SUPERAUTH_CSP_ENABLED', true),
            'default_src' => env('SUPERAUTH_CSP_DEFAULT_SRC', "'self'"),
            'script_src' => env('SUPERAUTH_CSP_SCRIPT_SRC', "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net"),
            'style_src' => env('SUPERAUTH_CSP_STYLE_SRC', "'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.tailwindcss.com"),
            'font_src' => env('SUPERAUTH_CSP_FONT_SRC', "'self' https://fonts.bunny.net https://fonts.gstatic.com"),
            'img_src' => env('SUPERAUTH_CSP_IMG_SRC', "'self' data: https: blob:"),
            'connect_src' => env('SUPERAUTH_CSP_CONNECT_SRC', "'self' https://api.pwnedpasswords.com"),
            'frame_ancestors' => env('SUPERAUTH_CSP_FRAME_ANCESTORS', "'none'"),
            'base_uri' => env('SUPERAUTH_CSP_BASE_URI', "'self'"),
            'form_action' => env('SUPERAUTH_CSP_FORM_ACTION', "'self'"),
            'upgrade_insecure_requests' => env('SUPERAUTH_CSP_UPGRADE_INSECURE_REQUESTS', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'enabled' => env('SUPERAUTH_RATE_LIMITING_ENABLED', true),
        'login' => [
            'max_attempts' => env('SUPERAUTH_LOGIN_RATE_LIMIT_ATTEMPTS', 5),
            'decay_minutes' => env('SUPERAUTH_LOGIN_RATE_LIMIT_DECAY', 15),
        ],
        'api' => [
            'max_attempts' => env('SUPERAUTH_API_RATE_LIMIT_ATTEMPTS', 60),
            'decay_minutes' => env('SUPERAUTH_API_RATE_LIMIT_DECAY', 1),
        ],
        'otp' => [
            'max_attempts' => env('SUPERAUTH_OTP_RATE_LIMIT_ATTEMPTS', 3),
            'decay_minutes' => env('SUPERAUTH_OTP_RATE_LIMIT_DECAY', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Security
    |--------------------------------------------------------------------------
    */
    'account' => [
        'lockout' => [
            'enabled' => env('SUPERAUTH_ACCOUNT_LOCKOUT_ENABLED', true),
            'max_attempts' => env('SUPERAUTH_ACCOUNT_LOCKOUT_ATTEMPTS', 5),
            'lockout_duration' => env('SUPERAUTH_ACCOUNT_LOCKOUT_DURATION', 30), // minutes
        ],
        'session' => [
            'timeout' => env('SUPERAUTH_SESSION_TIMEOUT', 120), // minutes
            'regenerate_on_login' => env('SUPERAUTH_SESSION_REGENERATE_ON_LOGIN', true),
            'secure_cookies' => env('SUPERAUTH_SESSION_SECURE_COOKIES', true),
            'http_only_cookies' => env('SUPERAUTH_SESSION_HTTP_ONLY_COOKIES', true),
        ],
        'two_factor' => [
            'enabled' => env('SUPERAUTH_TWO_FACTOR_ENABLED', true),
            'required_for_admin' => env('SUPERAUTH_TWO_FACTOR_REQUIRED_FOR_ADMIN', true),
            'backup_codes' => [
                'enabled' => env('SUPERAUTH_TWO_FACTOR_BACKUP_CODES_ENABLED', true),
                'count' => env('SUPERAUTH_TWO_FACTOR_BACKUP_CODES_COUNT', 10),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Monitoring
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'enabled' => env('SUPERAUTH_SECURITY_MONITORING_ENABLED', true),
        'login_tracking' => [
            'enabled' => env('SUPERAUTH_LOGIN_TRACKING_ENABLED', true),
            'track_ip' => env('SUPERAUTH_LOGIN_TRACK_IP', true),
            'track_location' => env('SUPERAUTH_LOGIN_TRACK_LOCATION', true),
            'track_device' => env('SUPERAUTH_LOGIN_TRACK_DEVICE', true),
            'track_browser' => env('SUPERAUTH_LOGIN_TRACK_BROWSER', true),
        ],
        'anomaly_detection' => [
            'enabled' => env('SUPERAUTH_ANOMALY_DETECTION_ENABLED', true),
            'unusual_location' => env('SUPERAUTH_ANOMALY_UNUSUAL_LOCATION', true),
            'unusual_time' => env('SUPERAUTH_ANOMALY_UNUSUAL_TIME', true),
            'unusual_device' => env('SUPERAUTH_ANOMALY_UNUSUAL_DEVICE', true),
            'unusual_ip' => env('SUPERAUTH_ANOMALY_UNUSUAL_IP', true),
        ],
        'risk_scoring' => [
            'enabled' => env('SUPERAUTH_RISK_SCORING_ENABLED', true),
            'high_risk_threshold' => env('SUPERAUTH_RISK_HIGH_THRESHOLD', 70),
            'medium_risk_threshold' => env('SUPERAUTH_RISK_MEDIUM_THRESHOLD', 50),
            'low_risk_threshold' => env('SUPERAUTH_RISK_LOW_THRESHOLD', 30),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => env('SUPERAUTH_SECURITY_ROUTE_PREFIX', 'security'),
        'middleware' => ['web', 'auth'],
        'password_strength' => [
            'enabled' => true,
            'path' => '/password-strength',
            'name' => 'superauth.security.password-strength',
        ],
        'breach_check' => [
            'enabled' => true,
            'path' => '/breach-check',
            'name' => 'superauth.security.breach-check',
        ],
        'security_settings' => [
            'enabled' => true,
            'path' => '/settings',
            'name' => 'superauth.security.settings',
        ],
        'login_history' => [
            'enabled' => true,
            'path' => '/login-history',
            'name' => 'superauth.security.login-history',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | View Settings
    |--------------------------------------------------------------------------
    */
    'views' => [
        'layout' => 'superauth::shared.layouts.app',
        'password_strength' => 'superauth::features.security.password-strength',
        'breach_check' => 'superauth::features.security.breach-check',
        'security_settings' => 'superauth::features.security.settings',
        'login_history' => 'superauth::features.security.login-history',
        'two_factor' => 'superauth::features.security.two-factor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Settings
    |--------------------------------------------------------------------------
    */
    'events' => [
        'password_breach_detected' => 'SuperAuth\Events\Security\PasswordBreachDetected',
        'password_strength_analyzed' => 'SuperAuth\Events\Security\PasswordStrengthAnalyzed',
        'unusual_login_detected' => 'SuperAuth\Events\Security\UnusualLoginDetected',
        'high_risk_login' => 'SuperAuth\Events\Security\HighRiskLogin',
        'security_alert' => 'SuperAuth\Events\Security\SecurityAlert',
        'two_factor_enabled' => 'SuperAuth\Events\Security\TwoFactorEnabled',
        'two_factor_disabled' => 'SuperAuth\Events\Security\TwoFactorDisabled',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('SUPERAUTH_SECURITY_CACHE_ENABLED', true),
        'ttl' => env('SUPERAUTH_SECURITY_CACHE_TTL', 3600),
        'prefix' => 'superauth:security:',
    ],
];
