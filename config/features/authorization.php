<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorization Feature Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Authorization
    | feature of the SuperAuth package.
    |
    */

    'enabled' => env('SUPERAUTH_AUTHZ_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Role Management
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'enabled' => env('SUPERAUTH_ROLES_ENABLED', true),
        'hierarchy' => [
            'enabled' => env('SUPERAUTH_ROLE_HIERARCHY_ENABLED', true),
            'max_level' => env('SUPERAUTH_ROLE_MAX_LEVEL', 100),
            'min_level' => env('SUPERAUTH_ROLE_MIN_LEVEL', 1),
        ],
        'expiration' => [
            'enabled' => env('SUPERAUTH_ROLE_EXPIRATION_ENABLED', true),
            'default_duration' => env('SUPERAUTH_ROLE_DEFAULT_DURATION', 365), // days
            'cleanup_enabled' => env('SUPERAUTH_ROLE_CLEANUP_ENABLED', true),
        ],
        'default_roles' => [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'level' => 100,
                'is_active' => true,
                'expires_at' => null,
            ],
            'admin' => [
                'display_name' => 'Administrator',
                'description' => 'Administrative access to manage users and content',
                'level' => 80,
                'is_active' => true,
                'expires_at' => null,
            ],
            'moderator' => [
                'display_name' => 'Moderator',
                'description' => 'Moderation access to manage content and users',
                'level' => 60,
                'is_active' => true,
                'expires_at' => null,
            ],
            'user' => [
                'display_name' => 'User',
                'description' => 'Standard user access',
                'level' => 20,
                'is_active' => true,
                'expires_at' => null,
            ],
            'guest' => [
                'display_name' => 'Guest',
                'description' => 'Limited guest access',
                'level' => 10,
                'is_active' => true,
                'expires_at' => null,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Management
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'enabled' => env('SUPERAUTH_PERMISSIONS_ENABLED', true),
        'categories' => [
            'users' => 'User Management',
            'roles' => 'Role Management',
            'permissions' => 'Permission Management',
            'admin' => 'Administration',
            'security' => 'Security',
            'notifications' => 'Notifications',
            'ai' => 'AI Features',
        ],
        'default_permissions' => [
            // User Management
            'users.create' => [
                'display_name' => 'Create Users',
                'description' => 'Create new users',
                'category' => 'users',
                'is_system' => true,
            ],
            'users.read' => [
                'display_name' => 'View Users',
                'description' => 'View user information',
                'category' => 'users',
                'is_system' => true,
            ],
            'users.update' => [
                'display_name' => 'Update Users',
                'description' => 'Update user information',
                'category' => 'users',
                'is_system' => true,
            ],
            'users.delete' => [
                'display_name' => 'Delete Users',
                'description' => 'Delete users',
                'category' => 'users',
                'is_system' => true,
            ],
            'users.manage' => [
                'display_name' => 'Manage Users',
                'description' => 'Full user management',
                'category' => 'users',
                'is_system' => true,
            ],
            // Role Management
            'roles.create' => [
                'display_name' => 'Create Roles',
                'description' => 'Create new roles',
                'category' => 'roles',
                'is_system' => true,
            ],
            'roles.read' => [
                'display_name' => 'View Roles',
                'description' => 'View role information',
                'category' => 'roles',
                'is_system' => true,
            ],
            'roles.update' => [
                'display_name' => 'Update Roles',
                'description' => 'Update role information',
                'category' => 'roles',
                'is_system' => true,
            ],
            'roles.delete' => [
                'display_name' => 'Delete Roles',
                'description' => 'Delete roles',
                'category' => 'roles',
                'is_system' => true,
            ],
            'roles.manage' => [
                'display_name' => 'Manage Roles',
                'description' => 'Full role management',
                'category' => 'roles',
                'is_system' => true,
            ],
            // Permission Management
            'permissions.create' => [
                'display_name' => 'Create Permissions',
                'description' => 'Create new permissions',
                'category' => 'permissions',
                'is_system' => true,
            ],
            'permissions.read' => [
                'display_name' => 'View Permissions',
                'description' => 'View permission information',
                'category' => 'permissions',
                'is_system' => true,
            ],
            'permissions.update' => [
                'display_name' => 'Update Permissions',
                'description' => 'Update permission information',
                'category' => 'permissions',
                'is_system' => true,
            ],
            'permissions.delete' => [
                'display_name' => 'Delete Permissions',
                'description' => 'Delete permissions',
                'category' => 'permissions',
                'is_system' => true,
            ],
            'permissions.manage' => [
                'display_name' => 'Manage Permissions',
                'description' => 'Full permission management',
                'category' => 'permissions',
                'is_system' => true,
            ],
            // Admin Dashboard
            'admin.dashboard' => [
                'display_name' => 'Admin Dashboard',
                'description' => 'Access admin dashboard',
                'category' => 'admin',
                'is_system' => true,
            ],
            'admin.settings' => [
                'display_name' => 'Admin Settings',
                'description' => 'Access admin settings',
                'category' => 'admin',
                'is_system' => true,
            ],
            'admin.reports' => [
                'display_name' => 'Admin Reports',
                'description' => 'Access admin reports',
                'category' => 'admin',
                'is_system' => true,
            ],
            'admin.logs' => [
                'display_name' => 'Admin Logs',
                'description' => 'Access admin logs',
                'category' => 'admin',
                'is_system' => true,
            ],
            // Security Features
            'security.breach_check' => [
                'display_name' => 'Password Breach Check',
                'description' => 'Check password breaches',
                'category' => 'security',
                'is_system' => true,
            ],
            'security.password_strength' => [
                'display_name' => 'Password Strength',
                'description' => 'Analyze password strength',
                'category' => 'security',
                'is_system' => true,
            ],
            'security.ai_monitoring' => [
                'display_name' => 'AI Monitoring',
                'description' => 'Access AI security monitoring',
                'category' => 'security',
                'is_system' => true,
            ],
            'security.notifications' => [
                'display_name' => 'Security Notifications',
                'description' => 'Manage security notifications',
                'category' => 'security',
                'is_system' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Control
    |--------------------------------------------------------------------------
    */
    'access_control' => [
        'enabled' => env('SUPERAUTH_ACCESS_CONTROL_ENABLED', true),
        'middleware' => [
            'role_based' => 'SuperAuth\Middleware\RoleBasedAccessMiddleware',
            'permission_based' => 'SuperAuth\Middleware\PermissionBasedAccessMiddleware',
            'feature_based' => 'SuperAuth\Middleware\FeatureAccessMiddleware',
        ],
        'policies' => [
            'enabled' => env('SUPERAUTH_POLICIES_ENABLED', true),
            'auto_discovery' => env('SUPERAUTH_POLICIES_AUTO_DISCOVERY', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => env('SUPERAUTH_AUTHZ_ROUTE_PREFIX', 'admin'),
        'middleware' => ['web', 'auth', 'role:admin'],
        'roles' => [
            'enabled' => true,
            'path' => '/roles',
            'name' => 'superauth.admin.roles',
        ],
        'permissions' => [
            'enabled' => true,
            'path' => '/permissions',
            'name' => 'superauth.admin.permissions',
        ],
        'users' => [
            'enabled' => true,
            'path' => '/users',
            'name' => 'superauth.admin.users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | View Settings
    |--------------------------------------------------------------------------
    */
    'views' => [
        'layout' => 'superauth::shared.layouts.admin',
        'roles' => 'superauth::features.authorization.roles',
        'permissions' => 'superauth::features.authorization.permissions',
        'users' => 'superauth::features.authorization.users',
        'role_management' => 'superauth::features.authorization.role-management',
        'permission_management' => 'superauth::features.authorization.permission-management',
        'user_role_assignment' => 'superauth::features.authorization.user-role-assignment',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Settings
    |--------------------------------------------------------------------------
    */
    'events' => [
        'role_created' => 'SuperAuth\Events\Authorization\RoleCreated',
        'role_updated' => 'SuperAuth\Events\Authorization\RoleUpdated',
        'role_deleted' => 'SuperAuth\Events\Authorization\RoleDeleted',
        'permission_created' => 'SuperAuth\Events\Authorization\PermissionCreated',
        'permission_updated' => 'SuperAuth\Events\Authorization\PermissionUpdated',
        'permission_deleted' => 'SuperAuth\Events\Authorization\PermissionDeleted',
        'user_role_assigned' => 'SuperAuth\Events\Authorization\UserRoleAssigned',
        'user_role_removed' => 'SuperAuth\Events\Authorization\UserRoleRemoved',
        'user_permission_granted' => 'SuperAuth\Events\Authorization\UserPermissionGranted',
        'user_permission_revoked' => 'SuperAuth\Events\Authorization\UserPermissionRevoked',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('SUPERAUTH_AUTHZ_CACHE_ENABLED', true),
        'ttl' => env('SUPERAUTH_AUTHZ_CACHE_TTL', 3600),
        'prefix' => 'superauth:authz:',
    ],
];
