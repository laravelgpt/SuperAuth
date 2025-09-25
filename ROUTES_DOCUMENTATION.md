# 🛣️ SuperAuth Routes Documentation

## 📋 **ROUTE STRUCTURE OVERVIEW**

SuperAuth provides a comprehensive routing system with dedicated route files for different functionalities:

- **`routes/web.php`** - Main web routes
- **`routes/api.php`** - API endpoints
- **`routes/auth.php`** - Authentication routes
- **`routes/admin.php`** - Admin panel routes
- **`routes/console.php`** - Console commands

## 🌐 **WEB ROUTES** (`routes/web.php`)

### **Guest Routes** (Unauthenticated)
```php
// Authentication
GET  /login                    - Show login form
POST /login                    - Process login
GET  /register                 - Show registration form
POST /register                 - Process registration

// Password Reset
GET  /forgot-password          - Show forgot password form
POST /forgot-password          - Send reset link
GET  /reset-password/{token}   - Show reset password form
POST /reset-password           - Process password reset

// OTP Verification
GET  /otp-verification         - Show OTP verification form
POST /otp-verification         - Verify OTP
POST /otp/resend              - Resend OTP

// Social Authentication
GET  /social/{provider}        - Redirect to social provider
GET  /social/{provider}/callback - Handle social callback
```

### **Authenticated Routes**
```php
// Basic Auth
POST /logout                   - Logout user
GET  /dashboard                - User dashboard
GET  /profile                  - User profile
POST /profile                  - Update profile

// Admin Routes (role:admin middleware)
GET  /admin/                   - Admin dashboard
GET  /admin/users              - User management
GET  /admin/users/{user}       - Show user details
PUT  /admin/users/{user}       - Update user
DELETE /admin/users/{user}     - Delete user
POST /admin/users/{user}/toggle-status - Toggle user status

// Role Management
GET  /admin/roles              - Role management
POST /admin/roles              - Create role
PUT  /admin/roles/{role}      - Update role
DELETE /admin/roles/{role}     - Delete role

// User Role Assignment
GET  /admin/users/{user}/roles - User roles
POST /admin/users/{user}/roles - Assign role
DELETE /admin/users/{user}/roles/{role} - Remove role

// AI Dashboard
GET  /admin/ai-dashboard       - AI dashboard
GET  /admin/analytics          - Analytics
GET  /admin/security-monitoring - Security monitoring
```

## 🔌 **API ROUTES** (`routes/api.php`)

### **Authentication API** (`/auth` prefix)
```php
// Guest API Routes
POST /auth/login               - API login
POST /auth/register            - API registration
POST /auth/forgot-password     - API forgot password
POST /auth/reset-password      - API reset password
POST /auth/otp-verify          - API OTP verification
POST /auth/otp/resend          - API resend OTP
POST /auth/social/{provider}   - API social redirect
POST /auth/social/{provider}/callback - API social callback

// Authenticated API Routes (auth:sanctum middleware)
POST /auth/logout              - API logout
GET  /auth/user                - Get user data
PUT  /auth/user                - Update user
POST /auth/user/avatar         - Update avatar
POST /auth/change-password     - Change password
GET  /auth/social-accounts     - Get social accounts
DELETE /auth/social-accounts/{provider} - Disconnect social

// Admin API Routes (role:admin middleware)
GET  /auth/admin/users         - API user management
GET  /auth/admin/users/{user}  - API show user
PUT  /auth/admin/users/{user}  - API update user
DELETE /auth/admin/users/{user} - API delete user
POST /auth/admin/users/{user}/toggle-status - API toggle status

// Role Management API
GET  /auth/admin/roles         - API roles
POST /auth/admin/roles         - API create role
PUT  /auth/admin/roles/{role}  - API update role
DELETE /auth/admin/roles/{role} - API delete role

// Analytics API
GET  /auth/admin/analytics     - API analytics
GET  /auth/admin/analytics/users - API user analytics
GET  /auth/admin/analytics/security - API security analytics

// AI Dashboard API
GET  /auth/admin/ai-dashboard  - API AI dashboard
GET  /auth/admin/ai-insights   - API AI insights
GET  /auth/admin/security-monitoring - API security monitoring

// Notifications API
GET  /auth/admin/notifications - API notifications
POST /auth/admin/notifications/send - API send notification
PUT  /auth/admin/notifications/{notification}/read - API mark read
```

### **Public API Routes** (`/public` prefix)
```php
GET  /public/health            - Health check
GET  /public/status            - System status
```

## 🔐 **AUTHENTICATION ROUTES** (`routes/auth.php`)

### **Guest Authentication Routes**
```php
// Login
GET  /login                    - Show login form
POST /login                    - Process login

// Registration
GET  /register                 - Show registration form
POST /register                 - Process registration

// Password Reset
GET  /forgot-password          - Show forgot password form
POST /forgot-password          - Send reset link
GET  /reset-password/{token}   - Show reset password form
POST /reset-password           - Process password reset

// OTP Verification
GET  /otp-verification         - Show OTP verification form
POST /otp-verification         - Verify OTP
POST /otp/resend              - Resend OTP

// Social Authentication
GET  /social/{provider}        - Redirect to social provider
GET  /social/{provider}/callback - Handle social callback

// Email Verification
GET  /email/verify             - Show email verification form
POST /email/verify             - Verify email
POST /email/resend             - Resend email verification
```

### **Authenticated Authentication Routes**
```php
// Basic Auth
POST /logout                   - Logout user
GET  /dashboard                - User dashboard

// Profile Management
GET  /profile                  - User profile
POST /profile                  - Update profile
POST /profile/avatar           - Update avatar
POST /profile/password         - Change password

// Social Account Management
GET  /social-accounts          - Social accounts
DELETE /social-accounts/{provider} - Disconnect social

// Security
GET  /security                - Security settings
POST /security/enable-2fa      - Enable 2FA
POST /security/disable-2fa     - Disable 2FA
GET  /security/sessions        - Active sessions
DELETE /security/sessions/{session} - Revoke session

// Notifications
GET  /notifications           - User notifications
PUT  /notifications/{notification}/read - Mark notification read
PUT  /notifications/read-all  - Mark all notifications read
```

## 👑 **ADMIN ROUTES** (`routes/admin.php`)

### **Admin Dashboard** (role:admin middleware)
```php
GET  /admin/                   - Admin dashboard
GET  /admin/overview           - Admin overview
```

### **User Management**
```php
GET  /admin/users              - User list
GET  /admin/users/create       - Create user form
POST /admin/users              - Store user
GET  /admin/users/{user}       - Show user
GET  /admin/users/{user}/edit  - Edit user form
PUT  /admin/users/{user}       - Update user
DELETE /admin/users/{user}     - Delete user

// User Status Management
POST /admin/users/{user}/toggle-status - Toggle user status
POST /admin/users/{user}/activate - Activate user
POST /admin/users/{user}/deactivate - Deactivate user

// User Role Management
GET  /admin/users/{user}/roles - User roles
POST /admin/users/{user}/roles - Assign role
DELETE /admin/users/{user}/roles/{role} - Remove role

// User Permissions
GET  /admin/users/{user}/permissions - User permissions
POST /admin/users/{user}/permissions - Assign permission
DELETE /admin/users/{user}/permissions/{permission} - Remove permission

// Bulk Operations
POST /admin/users/bulk/activate - Bulk activate users
POST /admin/users/bulk/deactivate - Bulk deactivate users
POST /admin/users/bulk/delete - Bulk delete users
POST /admin/users/bulk/export - Bulk export users
```

### **Role Management**
```php
GET  /admin/roles              - Role list
GET  /admin/roles/create       - Create role form
POST /admin/roles              - Store role
GET  /admin/roles/{role}       - Show role
GET  /admin/roles/{role}/edit  - Edit role form
PUT  /admin/roles/{role}       - Update role
DELETE /admin/roles/{role}     - Delete role

// Role Permissions
GET  /admin/roles/{role}/permissions - Role permissions
POST /admin/roles/{role}/permissions - Assign permission to role
DELETE /admin/roles/{role}/permissions/{permission} - Remove permission from role

// Role Hierarchy
POST /admin/roles/{role}/move-up - Move role up
POST /admin/roles/{role}/move-down - Move role down
```

### **Permission Management**
```php
GET  /admin/permissions        - Permission list
GET  /admin/permissions/create - Create permission form
POST /admin/permissions        - Store permission
GET  /admin/permissions/{permission} - Show permission
GET  /admin/permissions/{permission}/edit - Edit permission form
PUT  /admin/permissions/{permission} - Update permission
DELETE /admin/permissions/{permission} - Delete permission
```

### **AI Dashboard**
```php
GET  /admin/ai/dashboard       - AI dashboard
GET  /admin/ai/insights        - AI insights
GET  /admin/ai/monitoring      - AI monitoring
GET  /admin/ai/anomalies       - AI anomalies
GET  /admin/ai/recommendations - AI recommendations
```

### **Analytics**
```php
GET  /admin/analytics          - Analytics dashboard
GET  /admin/analytics/users    - User analytics
GET  /admin/analytics/security - Security analytics
GET  /admin/analytics/performance - Performance analytics
GET  /admin/analytics/notifications - Notification analytics
```

### **Security Monitoring**
```php
GET  /admin/security/monitoring - Security monitoring
GET  /admin/security/threats   - Security threats
GET  /admin/security/incidents - Security incidents
GET  /admin/security/audit-log - Audit log
GET  /admin/security/login-attempts - Login attempts
```

### **Notification Management**
```php
GET  /admin/notifications     - Notification list
GET  /admin/notifications/create - Create notification form
POST /admin/notifications     - Store notification
GET  /admin/notifications/{notification} - Show notification
PUT  /admin/notifications/{notification} - Update notification
DELETE /admin/notifications/{notification} - Delete notification

// Notification Sending
POST /admin/notifications/send - Send notification
POST /admin/notifications/bulk-send - Bulk send notification
```

### **System Settings**
```php
GET  /admin/settings          - System settings
PUT  /admin/settings          - Update settings

// Authentication Settings
GET  /admin/settings/auth     - Auth settings
PUT  /admin/settings/auth     - Update auth settings

// Security Settings
GET  /admin/settings/security - Security settings
PUT  /admin/settings/security - Update security settings

// Notification Settings
GET  /admin/settings/notifications - Notification settings
PUT  /admin/settings/notifications - Update notification settings
```

## 🖥️ **CONSOLE COMMANDS** (`routes/console.php`)

### **SuperAuth Commands**
```bash
# Installation
php artisan superauth:install                    - Install SuperAuth system
php artisan superauth:create-default-roles       - Create default roles
php artisan superauth:cleanup-expired-roles      - Cleanup expired roles
php artisan superauth:role-stats                 - Display role statistics
php artisan superauth:test-notifications         - Test notification system
php artisan superauth:ai-insights                - Generate AI insights
```

## 🔧 **MIDDLEWARE USAGE**

### **Route Middleware**
- **`guest`** - Only for unauthenticated users
- **`auth`** - Only for authenticated users
- **`auth:sanctum`** - API authentication
- **`role:admin`** - Admin role required
- **`permission:manage-users`** - Specific permission required
- **`feature:ai-dashboard`** - Feature access required

### **Security Middleware**
- **`security.headers`** - Security headers
- **`rate.limit`** - Rate limiting
- **`error.handling`** - Error handling

## 📝 **ROUTE NAMING CONVENTION**

### **Web Routes**
- `auth.*` - Authentication routes
- `admin.*` - Admin routes
- `api.*` - API routes

### **API Routes**
- `api.auth.*` - Authentication API
- `api.admin.*` - Admin API
- `api.public.*` - Public API

### **Admin Routes**
- `admin.users.*` - User management
- `admin.roles.*` - Role management
- `admin.permissions.*` - Permission management
- `admin.ai.*` - AI dashboard
- `admin.analytics.*` - Analytics
- `admin.security.*` - Security monitoring
- `admin.notifications.*` - Notification management
- `admin.settings.*` - System settings

## 🚀 **USAGE EXAMPLES**

### **Web Route Usage**
```php
// Redirect to login
return redirect()->route('auth.login');

// Redirect to dashboard
return redirect()->route('auth.dashboard');

// Redirect to admin
return redirect()->route('admin.dashboard');
```

### **API Route Usage**
```javascript
// Login API
fetch('/api/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
});

// Get user data
fetch('/api/auth/user', {
    headers: { 'Authorization': 'Bearer ' + token }
});
```

### **Admin Route Usage**
```php
// User management
Route::get('/admin/users', [AdminController::class, 'users'])
    ->name('admin.users');

// Role assignment
Route::post('/admin/users/{user}/roles', [AdminController::class, 'assignRole'])
    ->name('admin.users.roles.assign');
```

## 🎯 **ROUTE SECURITY**

### **Authentication Requirements**
- All admin routes require `role:admin` middleware
- API routes use `auth:sanctum` for token authentication
- Guest routes are protected by `guest` middleware

### **Rate Limiting**
- Login attempts: 5 per minute
- Registration: 3 per minute
- Password reset: 2 per minute
- OTP requests: 3 per minute

### **CSRF Protection**
- All web routes are protected by CSRF tokens
- API routes use token-based authentication
- Admin routes have additional security checks

## 📚 **ROUTE DOCUMENTATION**

This comprehensive routing system provides:

- **Complete Authentication Flow** - Login, registration, password reset, OTP
- **Social Authentication** - Google, Facebook, GitHub, Apple
- **Admin Management** - Users, roles, permissions, analytics
- **AI Dashboard** - Intelligent monitoring and insights
- **API Endpoints** - RESTful API for all functionality
- **Security Features** - Rate limiting, CSRF protection, role-based access
- **Console Commands** - Installation and management commands

**🚀 SuperAuth provides a complete routing solution for modern Laravel applications! 🎉**
