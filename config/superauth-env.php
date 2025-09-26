<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SuperAuth Environment Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all environment variables for the SuperAuth package.
    | Copy these settings to your .env file and configure them according to
    | your needs.
    |
    */

    'environment_variables' => [
        // =============================================================================
        // APPLICATION SETTINGS
        // =============================================================================
        'APP_NAME' => 'SuperAuth',
        'APP_ENV' => 'local',
        'APP_KEY' => 'base64:your-app-key-here',
        'APP_DEBUG' => 'true',
        'APP_URL' => 'http://localhost:8000',

        // =============================================================================
        // DATABASE CONFIGURATION
        // =============================================================================
        'DB_CONNECTION' => 'mysql',
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => '3306',
        'DB_DATABASE' => 'superauth',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => '',

        // =============================================================================
        // SUPERAUTH PACKAGE CONFIGURATION
        // =============================================================================

        // =============================================================================
        // AUTHENTICATION FEATURE
        // =============================================================================
        'SUPERAUTH_AUTH_ENABLED' => 'true',
        'SUPERAUTH_EMAIL_PASSWORD_ENABLED' => 'true',
        'SUPERAUTH_MIN_PASSWORD_LENGTH' => '8',
        'SUPERAUTH_REQUIRE_UPPERCASE' => 'true',
        'SUPERAUTH_REQUIRE_LOWERCASE' => 'true',
        'SUPERAUTH_REQUIRE_NUMBERS' => 'true',
        'SUPERAUTH_REQUIRE_SYMBOLS' => 'true',

        // OTP Configuration
        'SUPERAUTH_OTP_ENABLED' => 'true',
        'SUPERAUTH_OTP_LENGTH' => '6',
        'SUPERAUTH_OTP_EXPIRY_MINUTES' => '10',
        'SUPERAUTH_OTP_MAX_ATTEMPTS' => '3',
        'SUPERAUTH_OTP_DECAY_MINUTES' => '15',

        // Social Authentication
        'SUPERAUTH_SOCIAL_ENABLED' => 'true',
        'SUPERAUTH_GOOGLE_ENABLED' => 'true',
        'SUPERAUTH_FACEBOOK_ENABLED' => 'true',
        'SUPERAUTH_GITHUB_ENABLED' => 'true',
        'SUPERAUTH_APPLE_ENABLED' => 'true',

        // Google OAuth
        'GOOGLE_CLIENT_ID' => 'your-google-client-id',
        'GOOGLE_CLIENT_SECRET' => 'your-google-client-secret',
        'GOOGLE_REDIRECT_URI' => 'http://localhost:8000/auth/google/callback',

        // Facebook OAuth
        'FACEBOOK_CLIENT_ID' => 'your-facebook-client-id',
        'FACEBOOK_CLIENT_SECRET' => 'your-facebook-client-secret',
        'FACEBOOK_REDIRECT_URI' => 'http://localhost:8000/auth/facebook/callback',

        // GitHub OAuth
        'GITHUB_CLIENT_ID' => 'your-github-client-id',
        'GITHUB_CLIENT_SECRET' => 'your-github-client-secret',
        'GITHUB_REDIRECT_URI' => 'http://localhost:8000/auth/github/callback',

        // Apple OAuth
        'APPLE_CLIENT_ID' => 'your-apple-client-id',
        'APPLE_CLIENT_SECRET' => 'your-apple-client-secret',
        'APPLE_REDIRECT_URI' => 'http://localhost:8000/auth/apple/callback',

        // =============================================================================
        // AUTHORIZATION FEATURE
        // =============================================================================
        'SUPERAUTH_AUTHZ_ENABLED' => 'true',
        'SUPERAUTH_ROLES_ENABLED' => 'true',
        'SUPERAUTH_PERMISSIONS_ENABLED' => 'true',
        'SUPERAUTH_ACCESS_CONTROL_ENABLED' => 'true',
        'SUPERAUTH_ROLE_HIERARCHY_ENABLED' => 'true',
        'SUPERAUTH_ROLE_MAX_LEVEL' => '100',
        'SUPERAUTH_ROLE_MIN_LEVEL' => '1',
        'SUPERAUTH_ROLE_EXPIRATION_ENABLED' => 'true',
        'SUPERAUTH_ROLE_DEFAULT_DURATION' => '365',
        'SUPERAUTH_ROLE_CLEANUP_ENABLED' => 'true',
        'SUPERAUTH_POLICIES_ENABLED' => 'true',
        'SUPERAUTH_POLICIES_AUTO_DISCOVERY' => 'true',

        // =============================================================================
        // SECURITY FEATURE
        // =============================================================================
        'SUPERAUTH_SECURITY_ENABLED' => 'true',

        // Password Security
        'SUPERAUTH_PASSWORD_BREACH_CHECK_ENABLED' => 'true',
        'SUPERAUTH_BREACH_CHECK_API_URL' => 'https://api.pwnedpasswords.com/range/',
        'SUPERAUTH_BREACH_CHECK_TIMEOUT' => '10',
        'SUPERAUTH_BREACH_CHECK_CACHE_TTL' => '3600',
        'SUPERAUTH_BREACH_CHECK_RATE_LIMIT' => '100',

        'SUPERAUTH_PASSWORD_STRENGTH_ENABLED' => 'true',
        'SUPERAUTH_PASSWORD_STRENGTH_MIN_SCORE' => '60',
        'SUPERAUTH_PASSWORD_STRENGTH_REAL_TIME' => 'true',
        'SUPERAUTH_PASSWORD_MIN_LENGTH' => '8',
        'SUPERAUTH_PASSWORD_REQUIRE_UPPERCASE' => 'true',
        'SUPERAUTH_PASSWORD_REQUIRE_LOWERCASE' => 'true',
        'SUPERAUTH_PASSWORD_REQUIRE_NUMBERS' => 'true',
        'SUPERAUTH_PASSWORD_REQUIRE_SYMBOLS' => 'true',
        'SUPERAUTH_PASSWORD_MIN_UNIQUE_CHARS' => '8',

        'SUPERAUTH_PASSWORD_HISTORY_ENABLED' => 'true',
        'SUPERAUTH_PASSWORD_MAX_HISTORY' => '5',
        'SUPERAUTH_PASSWORD_PREVENT_REUSE' => 'true',

        // Security Headers
        'SUPERAUTH_SECURITY_HEADERS_ENABLED' => 'true',
        'SUPERAUTH_X_CONTENT_TYPE_OPTIONS' => 'nosniff',
        'SUPERAUTH_X_FRAME_OPTIONS' => 'DENY',
        'SUPERAUTH_X_XSS_PROTECTION' => '1; mode=block',
        'SUPERAUTH_REFERRER_POLICY' => 'strict-origin-when-cross-origin',
        'SUPERAUTH_PERMISSIONS_POLICY' => 'geolocation=(), microphone=(), camera=()',
        'SUPERAUTH_STRICT_TRANSPORT_SECURITY' => 'max-age=31536000; includeSubDomains',

        // Content Security Policy
        'SUPERAUTH_CSP_ENABLED' => 'true',
        'SUPERAUTH_CSP_DEFAULT_SRC' => "'self'",
        'SUPERAUTH_CSP_SCRIPT_SRC' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net",
        'SUPERAUTH_CSP_STYLE_SRC' => "'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.tailwindcss.com",
        'SUPERAUTH_CSP_FONT_SRC' => "'self' https://fonts.bunny.net https://fonts.gstatic.com",
        'SUPERAUTH_CSP_IMG_SRC' => "'self' data: https: blob:",
        'SUPERAUTH_CSP_CONNECT_SRC' => "'self' https://api.pwnedpasswords.com",
        'SUPERAUTH_CSP_FRAME_ANCESTORS' => "'none'",
        'SUPERAUTH_CSP_BASE_URI' => "'self'",
        'SUPERAUTH_CSP_FORM_ACTION' => "'self'",
        'SUPERAUTH_CSP_UPGRADE_INSECURE_REQUESTS' => 'true',

        // Rate Limiting
        'SUPERAUTH_RATE_LIMITING_ENABLED' => 'true',
        'SUPERAUTH_LOGIN_RATE_LIMIT_ATTEMPTS' => '5',
        'SUPERAUTH_LOGIN_RATE_LIMIT_DECAY' => '15',
        'SUPERAUTH_API_RATE_LIMIT_ATTEMPTS' => '60',
        'SUPERAUTH_API_RATE_LIMIT_DECAY' => '1',
        'SUPERAUTH_OTP_RATE_LIMIT_ATTEMPTS' => '3',
        'SUPERAUTH_OTP_RATE_LIMIT_DECAY' => '15',

        // Account Security
        'SUPERAUTH_ACCOUNT_LOCKOUT_ENABLED' => 'true',
        'SUPERAUTH_ACCOUNT_LOCKOUT_ATTEMPTS' => '5',
        'SUPERAUTH_ACCOUNT_LOCKOUT_DURATION' => '30',
        'SUPERAUTH_SESSION_TIMEOUT' => '120',
        'SUPERAUTH_SESSION_REGENERATE_ON_LOGIN' => 'true',
        'SUPERAUTH_SESSION_SECURE_COOKIES' => 'true',
        'SUPERAUTH_SESSION_HTTP_ONLY_COOKIES' => 'true',

        // Two-Factor Authentication
        'SUPERAUTH_TWO_FACTOR_ENABLED' => 'true',
        'SUPERAUTH_TWO_FACTOR_REQUIRED_FOR_ADMIN' => 'true',
        'SUPERAUTH_TWO_FACTOR_BACKUP_CODES_ENABLED' => 'true',
        'SUPERAUTH_TWO_FACTOR_BACKUP_CODES_COUNT' => '10',

        // Login Monitoring
        'SUPERAUTH_SECURITY_MONITORING_ENABLED' => 'true',
        'SUPERAUTH_LOGIN_TRACKING_ENABLED' => 'true',
        'SUPERAUTH_LOGIN_TRACK_IP' => 'true',
        'SUPERAUTH_LOGIN_TRACK_LOCATION' => 'true',
        'SUPERAUTH_LOGIN_TRACK_DEVICE' => 'true',
        'SUPERAUTH_LOGIN_TRACK_BROWSER' => 'true',

        // Anomaly Detection
        'SUPERAUTH_ANOMALY_DETECTION_ENABLED' => 'true',
        'SUPERAUTH_ANOMALY_UNUSUAL_LOCATION' => 'true',
        'SUPERAUTH_ANOMALY_UNUSUAL_TIME' => 'true',
        'SUPERAUTH_ANOMALY_UNUSUAL_DEVICE' => 'true',
        'SUPERAUTH_ANOMALY_UNUSUAL_IP' => 'true',

        // Risk Scoring
        'SUPERAUTH_RISK_SCORING_ENABLED' => 'true',
        'SUPERAUTH_RISK_HIGH_THRESHOLD' => '70',
        'SUPERAUTH_RISK_MEDIUM_THRESHOLD' => '50',
        'SUPERAUTH_RISK_LOW_THRESHOLD' => '30',

        // =============================================================================
        // NOTIFICATIONS FEATURE
        // =============================================================================
        'SUPERAUTH_NOTIFICATIONS_ENABLED' => 'true',

        // Email Notifications
        'SUPERAUTH_EMAIL_ENABLED' => 'true',
        'SUPERAUTH_EMAIL_FROM_ADDRESS' => 'noreply@example.com',
        'SUPERAUTH_EMAIL_FROM_NAME' => 'SuperAuth',
        'SUPERAUTH_EMAIL_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_EMAIL_QUEUE_NAME' => 'superauth-emails',

        // SMS Notifications
        'SUPERAUTH_SMS_ENABLED' => 'false',
        'SUPERAUTH_SMS_PROVIDER' => 'twilio',
        'SUPERAUTH_SMS_FROM_NUMBER' => '+1234567890',
        'SUPERAUTH_SMS_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_SMS_QUEUE_NAME' => 'superauth-sms',

        // Twilio SMS
        'TWILIO_SID' => 'your-twilio-sid',
        'TWILIO_TOKEN' => 'your-twilio-token',
        'TWILIO_FROM' => '+1234567890',

        // Telegram Notifications
        'SUPERAUTH_TELEGRAM_ENABLED' => 'false',
        'SUPERAUTH_TELEGRAM_BOT_TOKEN' => 'your-telegram-bot-token',
        'SUPERAUTH_TELEGRAM_CHAT_ID' => 'your-telegram-chat-id',
        'SUPERAUTH_TELEGRAM_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_TELEGRAM_QUEUE_NAME' => 'superauth-telegram',

        // Slack Notifications
        'SUPERAUTH_SLACK_ENABLED' => 'false',
        'SUPERAUTH_SLACK_WEBHOOK_URL' => 'your-slack-webhook-url',
        'SUPERAUTH_SLACK_CHANNEL' => '#notifications',
        'SUPERAUTH_SLACK_USERNAME' => 'SuperAuth',
        'SUPERAUTH_SLACK_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_SLACK_QUEUE_NAME' => 'superauth-slack',

        // WhatsApp Notifications
        'SUPERAUTH_WHATSAPP_ENABLED' => 'false',
        'SUPERAUTH_WHATSAPP_API_URL' => 'https://api.whatsapp.com',
        'SUPERAUTH_WHATSAPP_TOKEN' => 'your-whatsapp-token',
        'SUPERAUTH_WHATSAPP_PHONE_NUMBER' => '+1234567890',
        'SUPERAUTH_WHATSAPP_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_WHATSAPP_QUEUE_NAME' => 'superauth-whatsapp',

        // =============================================================================
        // AI FEATURE
        // =============================================================================
        'SUPERAUTH_AI_ENABLED' => 'true',
        'SUPERAUTH_AI_ANOMALY_DETECTION_ENABLED' => 'true',
        'SUPERAUTH_AI_RISK_SCORING_ENABLED' => 'true',
        'SUPERAUTH_AI_RECOMMENDATIONS_ENABLED' => 'true',
        'SUPERAUTH_AI_LEARNING_ENABLED' => 'true',
        'SUPERAUTH_AI_MODEL_PATH' => 'storage/ai/models',
        'SUPERAUTH_AI_TRAINING_DATA_PATH' => 'storage/ai/training',
        'SUPERAUTH_AI_PREDICTION_THRESHOLD' => '0.7',
        'SUPERAUTH_AI_UPDATE_INTERVAL' => '3600',

        // =============================================================================
        // UI CONFIGURATION
        // =============================================================================
        'SUPERAUTH_UI_THEME' => 'glass-morphism',
        'SUPERAUTH_UI_DARK_MODE' => 'true',
        'SUPERAUTH_UI_ANIMATIONS' => 'true',
        'SUPERAUTH_UI_RESPONSIVE' => 'true',
        'SUPERAUTH_UI_ACCESSIBILITY' => 'true',

        // Theme-specific settings
        'SUPERAUTH_AUTH_THEME' => 'glass-morphism',
        'SUPERAUTH_AUTH_DARK_MODE' => 'true',
        'SUPERAUTH_AUTH_ANIMATIONS' => 'true',
        'SUPERAUTH_AUTH_RESPONSIVE' => 'true',
        'SUPERAUTH_AUTH_ACCESSIBILITY' => 'true',

        // =============================================================================
        // ROUTE CONFIGURATION
        // =============================================================================
        'SUPERAUTH_AUTH_ROUTE_PREFIX' => 'auth',
        'SUPERAUTH_AUTHZ_ROUTE_PREFIX' => 'admin',
        'SUPERAUTH_SECURITY_ROUTE_PREFIX' => 'security',
        'SUPERAUTH_NOTIFICATIONS_ROUTE_PREFIX' => 'notifications',
        'SUPERAUTH_AI_ROUTE_PREFIX' => 'ai',

        // =============================================================================
        // CACHE CONFIGURATION
        // =============================================================================
        'SUPERAUTH_CACHE_ENABLED' => 'true',
        'SUPERAUTH_CACHE_DRIVER' => 'redis',
        'SUPERAUTH_CACHE_PREFIX' => 'superauth',
        'SUPERAUTH_CACHE_TTL' => '3600',

        // Feature-specific cache settings
        'SUPERAUTH_AUTH_CACHE_ENABLED' => 'true',
        'SUPERAUTH_AUTH_CACHE_TTL' => '3600',
        'SUPERAUTH_AUTHZ_CACHE_ENABLED' => 'true',
        'SUPERAUTH_AUTHZ_CACHE_TTL' => '3600',
        'SUPERAUTH_SECURITY_CACHE_ENABLED' => 'true',
        'SUPERAUTH_SECURITY_CACHE_TTL' => '3600',
        'SUPERAUTH_NOTIFICATIONS_CACHE_ENABLED' => 'true',
        'SUPERAUTH_NOTIFICATIONS_CACHE_TTL' => '3600',
        'SUPERAUTH_AI_CACHE_ENABLED' => 'true',
        'SUPERAUTH_AI_CACHE_TTL' => '3600',

        // =============================================================================
        // QUEUE CONFIGURATION
        // =============================================================================
        'SUPERAUTH_QUEUE_ENABLED' => 'true',
        'SUPERAUTH_QUEUE_DRIVER' => 'redis',
        'SUPERAUTH_QUEUE_CONNECTION' => 'default',
        'SUPERAUTH_QUEUE_PREFIX' => 'superauth',

        // =============================================================================
        // LOGGING CONFIGURATION
        // =============================================================================
        'SUPERAUTH_LOGGING_ENABLED' => 'true',
        'SUPERAUTH_LOGGING_LEVEL' => 'info',
        'SUPERAUTH_LOGGING_CHANNEL' => 'superauth',
        'SUPERAUTH_LOGGING_FORMAT' => 'json',
        'SUPERAUTH_LOGGING_MAX_FILES' => '30',

        // =============================================================================
        // MAIL CONFIGURATION
        // =============================================================================
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.gmail.com',
        'MAIL_PORT' => '587',
        'MAIL_USERNAME' => 'your-email@gmail.com',
        'MAIL_PASSWORD' => 'your-app-password',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_FROM_ADDRESS' => 'noreply@example.com',
        'MAIL_FROM_NAME' => 'SuperAuth',

        // =============================================================================
        // CACHE CONFIGURATION
        // =============================================================================
        'CACHE_DRIVER' => 'redis',
        'CACHE_PREFIX' => 'superauth',

        // =============================================================================
        // SESSION CONFIGURATION
        // =============================================================================
        'SESSION_DRIVER' => 'redis',
        'SESSION_LIFETIME' => '120',
        'SESSION_ENCRYPT' => 'false',
        'SESSION_PATH' => '/',
        'SESSION_DOMAIN' => 'null',
        'SESSION_SECURE_COOKIE' => 'false',
        'SESSION_HTTP_ONLY' => 'true',
        'SESSION_SAME_SITE' => 'lax',

        // =============================================================================
        // REDIS CONFIGURATION
        // =============================================================================
        'REDIS_HOST' => '127.0.0.1',
        'REDIS_PASSWORD' => 'null',
        'REDIS_PORT' => '6379',
        'REDIS_DB' => '0',

        // =============================================================================
        // BROADCAST CONFIGURATION
        // =============================================================================
        'BROADCAST_DRIVER' => 'pusher',
        'PUSHER_APP_ID' => 'your-pusher-app-id',
        'PUSHER_APP_KEY' => 'your-pusher-app-key',
        'PUSHER_APP_SECRET' => 'your-pusher-app-secret',
        'PUSHER_APP_CLUSTER' => 'mt1',
        'PUSHER_HOST' => '',
        'PUSHER_PORT' => '443',
        'PUSHER_SCHEME' => 'https',
        'PUSHER_APP_CLUSTER' => 'mt1',

        // =============================================================================
        // FILESYSTEM CONFIGURATION
        // =============================================================================
        'FILESYSTEM_DISK' => 'local',
        'AWS_ACCESS_KEY_ID' => '',
        'AWS_SECRET_ACCESS_KEY' => '',
        'AWS_DEFAULT_REGION' => 'us-east-1',
        'AWS_BUCKET' => '',
        'AWS_USE_PATH_STYLE_ENDPOINT' => 'false',

        // =============================================================================
        // VITE CONFIGURATION
        // =============================================================================
        'VITE_APP_NAME' => 'SuperAuth',
        'VITE_PUSHER_APP_KEY' => 'your-pusher-app-key',
        'VITE_PUSHER_HOST' => '',
        'VITE_PUSHER_PORT' => '443',
        'VITE_PUSHER_SCHEME' => 'https',
        'VITE_PUSHER_APP_CLUSTER' => 'mt1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Configuration Groups
    |--------------------------------------------------------------------------
    */
    'groups' => [
        'authentication' => [
            'SUPERAUTH_AUTH_ENABLED',
            'SUPERAUTH_EMAIL_PASSWORD_ENABLED',
            'SUPERAUTH_OTP_ENABLED',
            'SUPERAUTH_SOCIAL_ENABLED',
            'GOOGLE_CLIENT_ID',
            'GOOGLE_CLIENT_SECRET',
            'FACEBOOK_CLIENT_ID',
            'FACEBOOK_CLIENT_SECRET',
            'GITHUB_CLIENT_ID',
            'GITHUB_CLIENT_SECRET',
            'APPLE_CLIENT_ID',
            'APPLE_CLIENT_SECRET',
        ],
        'authorization' => [
            'SUPERAUTH_AUTHZ_ENABLED',
            'SUPERAUTH_ROLES_ENABLED',
            'SUPERAUTH_PERMISSIONS_ENABLED',
            'SUPERAUTH_ACCESS_CONTROL_ENABLED',
            'SUPERAUTH_ROLE_HIERARCHY_ENABLED',
        ],
        'security' => [
            'SUPERAUTH_SECURITY_ENABLED',
            'SUPERAUTH_PASSWORD_BREACH_CHECK_ENABLED',
            'SUPERAUTH_PASSWORD_STRENGTH_ENABLED',
            'SUPERAUTH_SECURITY_HEADERS_ENABLED',
            'SUPERAUTH_RATE_LIMITING_ENABLED',
            'SUPERAUTH_TWO_FACTOR_ENABLED',
        ],
        'notifications' => [
            'SUPERAUTH_NOTIFICATIONS_ENABLED',
            'SUPERAUTH_EMAIL_ENABLED',
            'SUPERAUTH_SMS_ENABLED',
            'SUPERAUTH_TELEGRAM_ENABLED',
            'SUPERAUTH_SLACK_ENABLED',
            'SUPERAUTH_WHATSAPP_ENABLED',
        ],
        'ai' => [
            'SUPERAUTH_AI_ENABLED',
            'SUPERAUTH_AI_ANOMALY_DETECTION_ENABLED',
            'SUPERAUTH_AI_RISK_SCORING_ENABLED',
            'SUPERAUTH_AI_RECOMMENDATIONS_ENABLED',
        ],
        'ui' => [
            'SUPERAUTH_UI_THEME',
            'SUPERAUTH_UI_DARK_MODE',
            'SUPERAUTH_UI_ANIMATIONS',
            'SUPERAUTH_UI_RESPONSIVE',
            'SUPERAUTH_UI_ACCESSIBILITY',
        ],
        'cache' => [
            'SUPERAUTH_CACHE_ENABLED',
            'SUPERAUTH_CACHE_DRIVER',
            'SUPERAUTH_CACHE_PREFIX',
            'SUPERAUTH_CACHE_TTL',
        ],
        'queue' => [
            'SUPERAUTH_QUEUE_ENABLED',
            'SUPERAUTH_QUEUE_DRIVER',
            'SUPERAUTH_QUEUE_CONNECTION',
            'SUPERAUTH_QUEUE_PREFIX',
        ],
        'logging' => [
            'SUPERAUTH_LOGGING_ENABLED',
            'SUPERAUTH_LOGGING_LEVEL',
            'SUPERAUTH_LOGGING_CHANNEL',
            'SUPERAUTH_LOGGING_FORMAT',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Required Environment Variables
    |--------------------------------------------------------------------------
    */
    'required' => [
        'APP_NAME',
        'APP_KEY',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        'MAIL_MAILER',
        'MAIL_HOST',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional Environment Variables
    |--------------------------------------------------------------------------
    */
    'optional' => [
        'SUPERAUTH_GOOGLE_CLIENT_ID',
        'SUPERAUTH_GOOGLE_CLIENT_SECRET',
        'SUPERAUTH_FACEBOOK_CLIENT_ID',
        'SUPERAUTH_FACEBOOK_CLIENT_SECRET',
        'SUPERAUTH_GITHUB_CLIENT_ID',
        'SUPERAUTH_GITHUB_CLIENT_SECRET',
        'SUPERAUTH_APPLE_CLIENT_ID',
        'SUPERAUTH_APPLE_CLIENT_SECRET',
        'TWILIO_SID',
        'TWILIO_TOKEN',
        'SUPERAUTH_TELEGRAM_BOT_TOKEN',
        'SUPERAUTH_SLACK_WEBHOOK_URL',
        'SUPERAUTH_WHATSAPP_TOKEN',
    ],
];
