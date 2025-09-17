# Requirements Gathering Platform with AI Interviews

A Laravel-based platform that revolutionizes project requirements gathering through AI-powered interviews, seamless user onboarding, and comprehensive project management.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](https://opensource.org/licenses/MIT)

## 🌟 Features

### 🔐 **Unified Authentication System**
- **Passwordless Authentication**: OTP-based login via email
- **Unified Login/Signup**: Single flow handles both new and existing users
- **Laravel Passport Integration**: JWT tokens for secure API access
- **Role-Based Access Control**: Admin, vendor, and user roles with appropriate scopes
- **Device Management**: Track user devices and push notifications

### 🤖 **AI-Powered Requirements Gathering**
- **Interactive AI Interviews**: Intelligent conversation flows to extract project requirements
- **Multiple Interview Templates**: Pre-built templates for different project types
- **Quick Replies**: AI-suggested responses to streamline the interview process
- **Real-time Progress Tracking**: Monitor interview completion and quality

### 📋 **Comprehensive Project Management**
- **Project Versioning**: Track changes and maintain project history
- **Collaborative Workspaces**: Multi-user project collaboration
- **Export Capabilities**: Generate requirements documents in multiple formats
- **Template Library**: Reusable project templates and compliance requirements

### 🏢 **Vendor Management System**
- **Vendor Marketplace**: Connect with qualified service providers
- **Project Lead Generation**: Vendors can bid on projects
- **Review System**: Rate and review vendor performance
- **Matching Algorithm**: AI-powered vendor-project matching

### 📊 **Analytics & Insights**
- **Usage Analytics**: Track platform usage and user behavior
- **Feature Usage Metrics**: Monitor feature adoption and engagement
- **Project Success Tracking**: Measure project completion rates
- **Performance Dashboards**: Real-time platform analytics

## 🚀 Quick Start

### Prerequisites
- PHP 8.3 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for frontend assets)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd specfirst
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=specfirst
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Install Laravel Passport**
   ```bash
   php artisan passport:install
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

The application will be available at `http://127.0.0.1:8000`

## 📚 Swagger Documentation

Interactive Swagger UI documentation is available at:
```
http://127.0.0.1:8000/api/documentation
```

### 📋 **Swagger Features**
- **Interactive Testing**: Test endpoints directly from the browser
- **Request/Response Examples**: Complete payload examples for all endpoints
- **Authentication Support**: Built-in Bearer token authentication
- **Schema Validation**: Request and response schema documentation
- **Try It Out**: Execute real requests against the live application

### 🔐 **Authentication Documentation**
- **OTP Authentication**: Passwordless login with email verification
- **Unified Flow**: Single endpoint handles both login and signup
- **JWT Token Management**: Bearer token authentication with scopes
- **Session Management**: Token creation, refresh, and revocation

### 📝 **Documentation Generation**
The Swagger documentation is automatically generated from:
- **Controller Annotations**: OpenAPI 3.0 annotations in controllers
- **Model Schemas**: Automatic schema generation from Eloquent models  
- **Request Validation**: Documentation from Laravel form requests
- **Response Examples**: Real response examples from the application

### 🔧 **Swagger Configuration**
```php
// config/l5-swagger.php
'default' => 'default',
'documentations' => [
    'default' => [
        'api' => [
            'title' => 'Requirements Gathering Platform API',
            'version' => '1.0.0',
        ],
        'routes' => [
            'api' => 'api/documentation',
        ],
        'paths' => [
            'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
            'docs_json' => 'api-docs.json',
            'docs_yaml' => 'api-docs.yaml',
            'annotations' => [
                base_path('app/Http/Controllers'),
            ],
        ],
    ],
],
```

### 📖 **How to Use Swagger UI**
1. **Navigate** to `http://127.0.0.1:8000/api/documentation`
2. **Authenticate** using the "Authorize" button with Bearer token
3. **Explore** endpoints organized by tags (Authentication, Projects, etc.)
4. **Test** endpoints using the "Try it out" button
5. **View** request/response schemas and examples

## 🛡️ Security Features

### Authentication Security
- **OTP Rate Limiting**: Prevents spam and abuse
- **Token Expiration**: Configurable JWT token lifetimes
- **Scope-Based Access**: Granular permission control
- **Device Tracking**: Monitor login devices and locations

### Data Protection
- **Input Validation**: Comprehensive request validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Output sanitization
- **CSRF Protection**: Laravel's built-in CSRF tokens

### Testing Configuration
- **Static OTP**: Development uses `123456` for testing
- **No Email Sending**: OTP codes logged instead of emailed
- **Debug Logging**: Comprehensive error and activity logging

## 🔧 Configuration

### OTP Settings
```php
// In OtpService.php
- OTP Code: Static "123456" (development)
- Expiration: 10 minutes
- Max Attempts: 5
- Rate Limiting: 1 per minute
```

### Token Scopes
```php
// User roles and their API scopes
'admin' => ['*']  // Full access
'vendor' => ['read-projects', 'write-projects', 'manage-leads']
'user' => ['read-projects', 'write-projects', 'manage-collaborators']
```

## 🧪 Testing

### Swagger Testing
Use the interactive Swagger UI for comprehensive testing:
1. **Open** `http://127.0.0.1:8000/api/documentation`
2. **Test Authentication Flow**:
   - Send OTP using `/api/auth/send-otp`
   - Verify OTP using `/api/auth/verify-otp`
   - Copy the returned Bearer token
3. **Authorize** by clicking "Authorize" and pasting the token
4. **Test Protected Endpoints** like `/api/auth/me` and `/api/auth/logout`

### Development Testing Notes
- **OTP Code**: Always use `123456` for development
- **Email**: Any valid email format works (user created if new)
- **Rate Limiting**: Wait 1 minute between OTP requests for same email
- **Token Expiry**: Tokens expire after configured time (default: 6 months)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation for new features
- Use conventional commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: [Swagger UI](http://127.0.0.1:8000/api/documentation)
- **Issues**: [GitHub Issues](https://github.com/your-repo/issues)
- **Email**: support@specfirst.com

---

**Built with ❤️ using Laravel 11**
