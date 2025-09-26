# 🔧 SuperAuth Environment Configuration Guide

## 📊 **COMPREHENSIVE ENVIRONMENT SETUP**

### **🎯 Overview**
This guide provides complete environment configuration for the SuperAuth package with all dynamic features and settings.

## 🎯 **ENVIRONMENT VARIABLES BY FEATURE**

### **🔐 Authentication Feature**
```env
# Basic Authentication
SUPERAUTH_AUTH_ENABLED=true
SUPERAUTH_EMAIL_PASSWORD_ENABLED=true
SUPERAUTH_MIN_PASSWORD_LENGTH=8
SUPERAUTH_REQUIRE_UPPERCASE=true
SUPERAUTH_REQUIRE_LOWERCASE=true
SUPERAUTH_REQUIRE_NUMBERS=true
SUPERAUTH_REQUIRE_SYMBOLS=true

# OTP Configuration
SUPERAUTH_OTP_ENABLED=true
SUPERAUTH_OTP_LENGTH=6
SUPERAUTH_OTP_EXPIRY_MINUTES=10
SUPERAUTH_OTP_MAX_ATTEMPTS=3
SUPERAUTH_OTP_DECAY_MINUTES=15

# Social Authentication
SUPERAUTH_SOCIAL_ENABLED=true
SUPERAUTH_GOOGLE_ENABLED=true
SUPERAUTH_FACEBOOK_ENABLED=true
SUPERAUTH_GITHUB_ENABLED=true
SUPERAUTH_APPLE_ENABLED=true

# OAuth Providers
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback

APPLE_CLIENT_ID=your-apple-client-id
APPLE_CLIENT_SECRET=your-apple-client-secret
APPLE_REDIRECT_URI=http://localhost:8000/auth/apple/callback
```

### **👥 Authorization Feature**
```env
# Role and Permission Management
SUPERAUTH_AUTHZ_ENABLED=true
SUPERAUTH_ROLES_ENABLED=true
SUPERAUTH_PERMISSIONS_ENABLED=true
SUPERAUTH_ACCESS_CONTROL_ENABLED=true

# Role Hierarchy
SUPERAUTH_ROLE_HIERARCHY_ENABLED=true
SUPERAUTH_ROLE_MAX_LEVEL=100
SUPERAUTH_ROLE_MIN_LEVEL=1

# Role Expiration
SUPERAUTH_ROLE_EXPIRATION_ENABLED=true
SUPERAUTH_ROLE_DEFAULT_DURATION=365
SUPERAUTH_ROLE_CLEANUP_ENABLED=true

# Policies
SUPERAUTH_POLICIES_ENABLED=true
SUPERAUTH_POLICIES_AUTO_DISCOVERY=true
```

### **🔒 Security Feature**
```env
# Password Security
SUPERAUTH_SECURITY_ENABLED=true
SUPERAUTH_PASSWORD_BREACH_CHECK_ENABLED=true
SUPERAUTH_BREACH_CHECK_API_URL=https://api.pwnedpasswords.com/range/
SUPERAUTH_BREACH_CHECK_TIMEOUT=10
SUPERAUTH_BREACH_CHECK_CACHE_TTL=3600
SUPERAUTH_BREACH_CHECK_RATE_LIMIT=100

SUPERAUTH_PASSWORD_STRENGTH_ENABLED=true
SUPERAUTH_PASSWORD_STRENGTH_MIN_SCORE=60
SUPERAUTH_PASSWORD_STRENGTH_REAL_TIME=true
SUPERAUTH_PASSWORD_MIN_LENGTH=8
SUPERAUTH_PASSWORD_REQUIRE_UPPERCASE=true
SUPERAUTH_PASSWORD_REQUIRE_LOWERCASE=true
SUPERAUTH_PASSWORD_REQUIRE_NUMBERS=true
SUPERAUTH_PASSWORD_REQUIRE_SYMBOLS=true
SUPERAUTH_PASSWORD_MIN_UNIQUE_CHARS=8

SUPERAUTH_PASSWORD_HISTORY_ENABLED=true
SUPERAUTH_PASSWORD_MAX_HISTORY=5
SUPERAUTH_PASSWORD_PREVENT_REUSE=true

# Security Headers
SUPERAUTH_SECURITY_HEADERS_ENABLED=true
SUPERAUTH_X_CONTENT_TYPE_OPTIONS=nosniff
SUPERAUTH_X_FRAME_OPTIONS=DENY
SUPERAUTH_X_XSS_PROTECTION=1; mode=block
SUPERAUTH_REFERRER_POLICY=strict-origin-when-cross-origin
SUPERAUTH_PERMISSIONS_POLICY=geolocation=(), microphone=(), camera=()
SUPERAUTH_STRICT_TRANSPORT_SECURITY=max-age=31536000; includeSubDomains

# Content Security Policy
SUPERAUTH_CSP_ENABLED=true
SUPERAUTH_CSP_DEFAULT_SRC='self'
SUPERAUTH_CSP_SCRIPT_SRC='self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net
SUPERAUTH_CSP_STYLE_SRC='self' 'unsafe-inline' https://fonts.bunny.net https://cdn.tailwindcss.com
SUPERAUTH_CSP_FONT_SRC='self' https://fonts.bunny.net https://fonts.gstatic.com
SUPERAUTH_CSP_IMG_SRC='self' data: https: blob:
SUPERAUTH_CSP_CONNECT_SRC='self' https://api.pwnedpasswords.com
SUPERAUTH_CSP_FRAME_ANCESTORS='none'
SUPERAUTH_CSP_BASE_URI='self'
SUPERAUTH_CSP_FORM_ACTION='self'
SUPERAUTH_CSP_UPGRADE_INSECURE_REQUESTS=true

# Rate Limiting
SUPERAUTH_RATE_LIMITING_ENABLED=true
SUPERAUTH_LOGIN_RATE_LIMIT_ATTEMPTS=5
SUPERAUTH_LOGIN_RATE_LIMIT_DECAY=15
SUPERAUTH_API_RATE_LIMIT_ATTEMPTS=60
SUPERAUTH_API_RATE_LIMIT_DECAY=1
SUPERAUTH_OTP_RATE_LIMIT_ATTEMPTS=3
SUPERAUTH_OTP_RATE_LIMIT_DECAY=15

# Account Security
SUPERAUTH_ACCOUNT_LOCKOUT_ENABLED=true
SUPERAUTH_ACCOUNT_LOCKOUT_ATTEMPTS=5
SUPERAUTH_ACCOUNT_LOCKOUT_DURATION=30
SUPERAUTH_SESSION_TIMEOUT=120
SUPERAUTH_SESSION_REGENERATE_ON_LOGIN=true
SUPERAUTH_SESSION_SECURE_COOKIES=true
SUPERAUTH_SESSION_HTTP_ONLY_COOKIES=true

# Two-Factor Authentication
SUPERAUTH_TWO_FACTOR_ENABLED=true
SUPERAUTH_TWO_FACTOR_REQUIRED_FOR_ADMIN=true
SUPERAUTH_TWO_FACTOR_BACKUP_CODES_ENABLED=true
SUPERAUTH_TWO_FACTOR_BACKUP_CODES_COUNT=10

# Login Monitoring
SUPERAUTH_SECURITY_MONITORING_ENABLED=true
SUPERAUTH_LOGIN_TRACKING_ENABLED=true
SUPERAUTH_LOGIN_TRACK_IP=true
SUPERAUTH_LOGIN_TRACK_LOCATION=true
SUPERAUTH_LOGIN_TRACK_DEVICE=true
SUPERAUTH_LOGIN_TRACK_BROWSER=true

# Anomaly Detection
SUPERAUTH_ANOMALY_DETECTION_ENABLED=true
SUPERAUTH_ANOMALY_UNUSUAL_LOCATION=true
SUPERAUTH_ANOMALY_UNUSUAL_TIME=true
SUPERAUTH_ANOMALY_UNUSUAL_DEVICE=true
SUPERAUTH_ANOMALY_UNUSUAL_IP=true

# Risk Scoring
SUPERAUTH_RISK_SCORING_ENABLED=true
SUPERAUTH_RISK_HIGH_THRESHOLD=70
SUPERAUTH_RISK_MEDIUM_THRESHOLD=50
SUPERAUTH_RISK_LOW_THRESHOLD=30
```

### **📧 Notifications Feature**
```env
# Basic Notifications
SUPERAUTH_NOTIFICATIONS_ENABLED=true

# Email Notifications
SUPERAUTH_EMAIL_ENABLED=true
SUPERAUTH_EMAIL_FROM_ADDRESS=noreply@example.com
SUPERAUTH_EMAIL_FROM_NAME="SuperAuth"
SUPERAUTH_EMAIL_QUEUE_ENABLED=true
SUPERAUTH_EMAIL_QUEUE_NAME=superauth-emails

# SMS Notifications
SUPERAUTH_SMS_ENABLED=false
SUPERAUTH_SMS_PROVIDER=twilio
SUPERAUTH_SMS_FROM_NUMBER=+1234567890
SUPERAUTH_SMS_QUEUE_ENABLED=true
SUPERAUTH_SMS_QUEUE_NAME=superauth-sms

# Twilio SMS
TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_FROM=+1234567890

# Telegram Notifications
SUPERAUTH_TELEGRAM_ENABLED=false
SUPERAUTH_TELEGRAM_BOT_TOKEN=your-telegram-bot-token
SUPERAUTH_TELEGRAM_CHAT_ID=your-telegram-chat-id
SUPERAUTH_TELEGRAM_QUEUE_ENABLED=true
SUPERAUTH_TELEGRAM_QUEUE_NAME=superauth-telegram

# Slack Notifications
SUPERAUTH_SLACK_ENABLED=false
SUPERAUTH_SLACK_WEBHOOK_URL=your-slack-webhook-url
SUPERAUTH_SLACK_CHANNEL=#notifications
SUPERAUTH_SLACK_USERNAME=SuperAuth
SUPERAUTH_SLACK_QUEUE_ENABLED=true
SUPERAUTH_SLACK_QUEUE_NAME=superauth-slack

# WhatsApp Notifications
SUPERAUTH_WHATSAPP_ENABLED=false
SUPERAUTH_WHATSAPP_API_URL=https://api.whatsapp.com
SUPERAUTH_WHATSAPP_TOKEN=your-whatsapp-token
SUPERAUTH_WHATSAPP_PHONE_NUMBER=+1234567890
SUPERAUTH_WHATSAPP_QUEUE_ENABLED=true
SUPERAUTH_WHATSAPP_QUEUE_NAME=superauth-whatsapp
```

### **🤖 AI Feature**
```env
# AI Features
SUPERAUTH_AI_ENABLED=true
SUPERAUTH_AI_ANOMALY_DETECTION_ENABLED=true
SUPERAUTH_AI_RISK_SCORING_ENABLED=true
SUPERAUTH_AI_RECOMMENDATIONS_ENABLED=true
SUPERAUTH_AI_LEARNING_ENABLED=true
SUPERAUTH_AI_MODEL_PATH=storage/ai/models
SUPERAUTH_AI_TRAINING_DATA_PATH=storage/ai/training
SUPERAUTH_AI_PREDICTION_THRESHOLD=0.7
SUPERAUTH_AI_UPDATE_INTERVAL=3600
```

### **🎨 UI Configuration**
```env
# UI Settings
SUPERAUTH_UI_THEME=glass-morphism
SUPERAUTH_UI_DARK_MODE=true
SUPERAUTH_UI_ANIMATIONS=true
SUPERAUTH_UI_RESPONSIVE=true
SUPERAUTH_UI_ACCESSIBILITY=true

# Theme-specific settings
SUPERAUTH_AUTH_THEME=glass-morphism
SUPERAUTH_AUTH_DARK_MODE=true
SUPERAUTH_AUTH_ANIMATIONS=true
SUPERAUTH_AUTH_RESPONSIVE=true
SUPERAUTH_AUTH_ACCESSIBILITY=true
```

### **🛣️ Route Configuration**
```env
# Route Prefixes
SUPERAUTH_AUTH_ROUTE_PREFIX=auth
SUPERAUTH_AUTHZ_ROUTE_PREFIX=admin
SUPERAUTH_SECURITY_ROUTE_PREFIX=security
SUPERAUTH_NOTIFICATIONS_ROUTE_PREFIX=notifications
SUPERAUTH_AI_ROUTE_PREFIX=ai
```

### **💾 Cache Configuration**
```env
# Cache Settings
SUPERAUTH_CACHE_ENABLED=true
SUPERAUTH_CACHE_DRIVER=redis
SUPERAUTH_CACHE_PREFIX=superauth
SUPERAUTH_CACHE_TTL=3600

# Feature-specific cache settings
SUPERAUTH_AUTH_CACHE_ENABLED=true
SUPERAUTH_AUTH_CACHE_TTL=3600
SUPERAUTH_AUTHZ_CACHE_ENABLED=true
SUPERAUTH_AUTHZ_CACHE_TTL=3600
SUPERAUTH_SECURITY_CACHE_ENABLED=true
SUPERAUTH_SECURITY_CACHE_TTL=3600
SUPERAUTH_NOTIFICATIONS_CACHE_ENABLED=true
SUPERAUTH_NOTIFICATIONS_CACHE_TTL=3600
SUPERAUTH_AI_CACHE_ENABLED=true
SUPERAUTH_AI_CACHE_TTL=3600
```

### **📋 Queue Configuration**
```env
# Queue Settings
SUPERAUTH_QUEUE_ENABLED=true
SUPERAUTH_QUEUE_DRIVER=redis
SUPERAUTH_QUEUE_CONNECTION=default
SUPERAUTH_QUEUE_PREFIX=superauth
```

### **📝 Logging Configuration**
```env
# Logging Settings
SUPERAUTH_LOGGING_ENABLED=true
SUPERAUTH_LOGGING_LEVEL=info
SUPERAUTH_LOGGING_CHANNEL=superauth
SUPERAUTH_LOGGING_FORMAT=json
SUPERAUTH_LOGGING_MAX_FILES=30
```

## 🎯 **REQUIRED ENVIRONMENT VARIABLES**

### **🔧 Core Laravel Variables**
```env
APP_NAME="SuperAuth"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=superauth
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### **🔧 Cache and Session**
```env
CACHE_DRIVER=redis
CACHE_PREFIX=superauth

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### **🔧 Redis Configuration**
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

## 🎯 **OPTIONAL ENVIRONMENT VARIABLES**

### **🔧 OAuth Providers (Optional)**
```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret

# Facebook OAuth
FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret

# GitHub OAuth
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret

# Apple OAuth
APPLE_CLIENT_ID=your-apple-client-id
APPLE_CLIENT_SECRET=your-apple-client-secret
```

### **🔧 Notification Providers (Optional)**
```env
# Twilio SMS
TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_FROM=+1234567890

# Telegram
SUPERAUTH_TELEGRAM_BOT_TOKEN=your-telegram-bot-token
SUPERAUTH_TELEGRAM_CHAT_ID=your-telegram-chat-id

# Slack
SUPERAUTH_SLACK_WEBHOOK_URL=your-slack-webhook-url
SUPERAUTH_SLACK_CHANNEL=#notifications

# WhatsApp
SUPERAUTH_WHATSAPP_TOKEN=your-whatsapp-token
SUPERAUTH_WHATSAPP_PHONE_NUMBER=+1234567890
```

## 🎯 **ENVIRONMENT SETUP STEPS**

### **📋 Step 1: Copy Environment File**
```bash
cp config/superauth-env.php .env
```

### **📋 Step 2: Generate Application Key**
```bash
php artisan key:generate
```

### **📋 Step 3: Configure Database**
```bash
# Update database credentials in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=superauth
DB_USERNAME=root
DB_PASSWORD=your-password
```

### **📋 Step 4: Configure Mail**
```bash
# Update mail settings in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### **📋 Step 5: Configure Redis**
```bash
# Update Redis settings in .env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

### **📋 Step 6: Run Installation Wizard**
```bash
php artisan superauth:install-wizard
```

## 🎯 **ENVIRONMENT VALIDATION**

### **🔍 Check Configuration**
```php
use SuperAuth\Core\ConfigurationManager;

$configManager = app(ConfigurationManager::class);

// Validate authentication configuration
$errors = $configManager->validateConfig('authentication', $config);

// Get configuration recommendations
$recommendations = $configManager->getConfigRecommendations('authentication');
```

### **🔍 Check Feature Status**
```php
use SuperAuth\Core\FeatureManager;

$featureManager = app(FeatureManager::class);

// Check if features are enabled
$authEnabled = $featureManager->isEnabled('authentication');
$securityEnabled = $featureManager->isEnabled('security');

// Get feature statistics
$stats = $featureManager->getFeatureStats();
```

## 🎯 **ENVIRONMENT GROUPS**

### **📁 Feature Groups**
- **Authentication** - Login, register, OTP, social login
- **Authorization** - Roles, permissions, access control
- **Security** - Password strength, breach checking, monitoring
- **Notifications** - Email, SMS, Telegram, Slack, WhatsApp
- **AI** - Anomaly detection, risk scoring, recommendations
- **UI** - Themes, animations, responsiveness
- **Cache** - Redis, caching strategies
- **Queue** - Background job processing
- **Logging** - Log levels, channels, formats

## 🎯 **BEST PRACTICES**

### **✅ Security Best Practices**
1. **Never commit .env files** - Use .env.example as template
2. **Use strong passwords** - Generate secure passwords for all services
3. **Enable HTTPS** - Use secure connections in production
4. **Regular updates** - Keep all dependencies updated
5. **Monitor logs** - Check logs regularly for security issues

### **✅ Performance Best Practices**
1. **Use Redis** - Configure Redis for caching and sessions
2. **Enable queues** - Use background job processing
3. **Optimize database** - Use proper indexes and queries
4. **Cache frequently** - Cache expensive operations
5. **Monitor performance** - Use monitoring tools

### **✅ Development Best Practices**
1. **Environment separation** - Use different configs for dev/prod
2. **Feature toggles** - Use environment variables for feature control
3. **Configuration validation** - Validate all configuration values
4. **Documentation** - Document all environment variables
5. **Testing** - Test with different environment configurations

**This comprehensive environment configuration ensures SuperAuth works optimally in any environment! 🚀**
