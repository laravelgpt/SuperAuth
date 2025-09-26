<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SuperAuth Dynamic Routes Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the dynamic route configurations for SuperAuth.
    | Routes can be enabled/disabled based on feature flags and can be
    | customized per installation.
    |
    */

    'middleware' => [
        'web' => ['web'],
        'api' => ['api'],
        'auth' => ['auth'],
        'admin' => ['auth', 'role:admin'],
        'guest' => ['guest'],
        'throttle' => ['throttle:60,1'],
    ],

    'routes' => [
        'dashboard' => [
            'enabled' => true,
            'routes' => [
                [
                    'method' => 'get',
                    'path' => '/',
                    'action' => 'SuperAuth\Http\Controllers\DashboardController@index',
                    'name' => 'superauth.dashboard',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'get',
                    'path' => '/dashboard',
                    'action' => 'SuperAuth\Http\Controllers\DashboardController@index',
                    'name' => 'superauth.dashboard',
                    'middleware' => ['web', 'auth']
                ]
            ]
        ],

        'authentication' => [
            'enabled' => true,
            'routes' => [
                // Login routes
                [
                    'method' => 'get',
                    'path' => '/login',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showLoginForm',
                    'name' => 'superauth.login',
                    'middleware' => ['web', 'guest']
                ],
                [
                    'method' => 'post',
                    'path' => '/login',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@login',
                    'name' => 'superauth.login',
                    'middleware' => ['web', 'guest', 'throttle']
                ],
                
                // Registration routes
                [
                    'method' => 'get',
                    'path' => '/register',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showRegisterForm',
                    'name' => 'superauth.register',
                    'middleware' => ['web', 'guest']
                ],
                [
                    'method' => 'post',
                    'path' => '/register',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@register',
                    'name' => 'superauth.register',
                    'middleware' => ['web', 'guest', 'throttle']
                ],

                // Password reset routes
                [
                    'method' => 'get',
                    'path' => '/forgot-password',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showForgotPasswordForm',
                    'name' => 'superauth.forgot-password',
                    'middleware' => ['web', 'guest']
                ],
                [
                    'method' => 'post',
                    'path' => '/forgot-password',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@forgotPassword',
                    'name' => 'superauth.forgot-password',
                    'middleware' => ['web', 'guest', 'throttle']
                ],
                [
                    'method' => 'get',
                    'path' => '/reset-password/{token}',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showResetPasswordForm',
                    'name' => 'superauth.reset-password',
                    'middleware' => ['web', 'guest']
                ],
                [
                    'method' => 'post',
                    'path' => '/reset-password',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@resetPassword',
                    'name' => 'superauth.reset-password',
                    'middleware' => ['web', 'guest', 'throttle']
                ],

                // Email verification routes
                [
                    'method' => 'get',
                    'path' => '/verify-email',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showVerifyEmailForm',
                    'name' => 'superauth.verify-email',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'post',
                    'path' => '/verify-email/resend',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@resendVerification',
                    'name' => 'superauth.verify-email.resend',
                    'middleware' => ['web', 'auth', 'throttle']
                ],

                // Two-factor authentication routes
                [
                    'method' => 'get',
                    'path' => '/two-factor',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@showTwoFactorForm',
                    'name' => 'superauth.two-factor',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'post',
                    'path' => '/two-factor/verify',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@verifyTwoFactor',
                    'name' => 'superauth.two-factor.verify',
                    'middleware' => ['web', 'auth', 'throttle']
                ],
                [
                    'method' => 'post',
                    'path' => '/two-factor/recovery',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@verifyRecoveryCode',
                    'name' => 'superauth.two-factor.recovery',
                    'middleware' => ['web', 'auth', 'throttle']
                ],

                // Logout route
                [
                    'method' => 'post',
                    'path' => '/logout',
                    'action' => 'SuperAuth\Http\Controllers\AuthController@logout',
                    'name' => 'superauth.logout',
                    'middleware' => ['web', 'auth']
                ]
            ]
        ],

        'social_auth' => [
            'enabled' => env('SUPERAUTH_SOCIAL_AUTH_ENABLED', true),
            'routes' => [
                [
                    'method' => 'get',
                    'path' => '/auth/{provider}',
                    'action' => 'SuperAuth\Http\Controllers\SocialAuthController@redirect',
                    'name' => 'superauth.social.redirect',
                    'middleware' => ['web', 'guest'],
                    'constraints' => ['provider' => 'google|facebook|github|apple']
                ],
                [
                    'method' => 'get',
                    'path' => '/auth/{provider}/callback',
                    'action' => 'SuperAuth\Http\Controllers\SocialAuthController@callback',
                    'name' => 'superauth.social.callback',
                    'middleware' => ['web', 'guest'],
                    'constraints' => ['provider' => 'google|facebook|github|apple']
                ]
            ]
        ],

        'profile' => [
            'enabled' => true,
            'routes' => [
                [
                    'method' => 'get',
                    'path' => '/profile',
                    'action' => 'SuperAuth\Http\Controllers\ProfileController@show',
                    'name' => 'superauth.profile',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'put',
                    'path' => '/profile',
                    'action' => 'SuperAuth\Http\Controllers\ProfileController@update',
                    'name' => 'superauth.profile.update',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'post',
                    'path' => '/profile/avatar',
                    'action' => 'SuperAuth\Http\Controllers\ProfileController@updateAvatar',
                    'name' => 'superauth.profile.avatar',
                    'middleware' => ['web', 'auth']
                ],
                [
                    'method' => 'post',
                    'path' => '/profile/password',
                    'action' => 'SuperAuth\Http\Controllers\ProfileController@updatePassword',
                    'name' => 'superauth.profile.password',
                    'middleware' => ['web', 'auth']
                ]
            ]
        ],

        'admin' => [
            'enabled' => true,
            'routes' => [
                [
                    'method' => 'get',
                    'path' => '/admin',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@dashboard',
                    'name' => 'admin.dashboard',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'get',
                    'path' => '/admin/users',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@users',
                    'name' => 'admin.users',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'get',
                    'path' => '/admin/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@showUser',
                    'name' => 'admin.users.show',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'put',
                    'path' => '/admin/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@updateUser',
                    'name' => 'admin.users.update',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'delete',
                    'path' => '/admin/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@deleteUser',
                    'name' => 'admin.users.delete',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'get',
                    'path' => '/admin/roles',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@roles',
                    'name' => 'admin.roles',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'post',
                    'path' => '/admin/roles',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@createRole',
                    'name' => 'admin.roles.create',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'put',
                    'path' => '/admin/roles/{role}',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@updateRole',
                    'name' => 'admin.roles.update',
                    'middleware' => ['web', 'admin']
                ],
                [
                    'method' => 'delete',
                    'path' => '/admin/roles/{role}',
                    'action' => 'SuperAuth\Http\Controllers\AdminController@deleteRole',
                    'name' => 'admin.roles.delete',
                    'middleware' => ['web', 'admin']
                ]
            ]
        ],

        'api' => [
            'enabled' => true,
            'routes' => [
                // Authentication API
                [
                    'method' => 'post',
                    'path' => '/api/auth/login',
                    'action' => 'SuperAuth\Http\Controllers\Api\AuthController@login',
                    'name' => 'api.auth.login',
                    'middleware' => ['api', 'throttle']
                ],
                [
                    'method' => 'post',
                    'path' => '/api/auth/register',
                    'action' => 'SuperAuth\Http\Controllers\Api\AuthController@register',
                    'name' => 'api.auth.register',
                    'middleware' => ['api', 'throttle']
                ],
                [
                    'method' => 'post',
                    'path' => '/api/auth/logout',
                    'action' => 'SuperAuth\Http\Controllers\Api\AuthController@logout',
                    'name' => 'api.auth.logout',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'post',
                    'path' => '/api/auth/refresh',
                    'action' => 'SuperAuth\Http\Controllers\Api\AuthController@refresh',
                    'name' => 'api.auth.refresh',
                    'middleware' => ['api', 'auth:sanctum']
                ],

                // User API
                [
                    'method' => 'get',
                    'path' => '/api/users',
                    'action' => 'SuperAuth\Http\Controllers\Api\UserController@index',
                    'name' => 'api.users.index',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'get',
                    'path' => '/api/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\Api\UserController@show',
                    'name' => 'api.users.show',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'put',
                    'path' => '/api/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\Api\UserController@update',
                    'name' => 'api.users.update',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'delete',
                    'path' => '/api/users/{user}',
                    'action' => 'SuperAuth\Http\Controllers\Api\UserController@destroy',
                    'name' => 'api.users.destroy',
                    'middleware' => ['api', 'auth:sanctum']
                ],

                // Security API
                [
                    'method' => 'post',
                    'path' => '/api/security/breach-check',
                    'action' => 'SuperAuth\Http\Controllers\Api\SecurityController@breachCheck',
                    'name' => 'api.security.breach-check',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'post',
                    'path' => '/api/security/password-strength',
                    'action' => 'SuperAuth\Http\Controllers\Api\SecurityController@passwordStrength',
                    'name' => 'api.security.password-strength',
                    'middleware' => ['api', 'auth:sanctum']
                ],
                [
                    'method' => 'get',
                    'path' => '/api/security/login-history',
                    'action' => 'SuperAuth\Http\Controllers\Api\SecurityController@loginHistory',
                    'name' => 'api.security.login-history',
                    'middleware' => ['api', 'auth:sanctum']
                ]
            ]
        ],

        'theme' => [
            'enabled' => true,
            'routes' => [
                [
                    'method' => 'post',
                    'path' => '/theme',
                    'action' => 'SuperAuth\Http\Controllers\ThemeController@toggle',
                    'name' => 'superauth.theme',
                    'middleware' => ['web']
                ]
            ]
        ]
    ]
];
