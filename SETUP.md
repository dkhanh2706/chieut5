# IT Project Management - Authentication & Dashboard Setup

## Overview

This setup provides a complete authentication system with login, registration, forgotten password, and a project management dashboard for your Laravel application.

## Features Implemented

### 1. **Authentication System**

- ✅ User Registration
- ✅ User Login with Remember Me
- ✅ Forgot Password with Email Reset Link
- ✅ Password Reset
- ✅ User Logout

### 2. **Pages Created**

- **Login Page** (`/login`) - User login form
- **Registration Page** (`/register`) - New user registration
- **Forgot Password Page** (`/forgot-password`) - Password recovery
- **Reset Password Page** (`/reset-password/{token}`) - Set new password
- **Dashboard Page** (`/dashboard`) - IT Project Management interface
- **Home Page** (`/`) - Welcome page

### 3. **Project Management Dashboard**

- Statistics dashboard showing:
    - Total Projects
    - Projects In Progress
    - Completed Projects
    - Team Members
- Create new projects
- View project status and progress
- Edit and delete projects
- Beautiful responsive UI with Bootstrap

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Authentication logic
│   │   └── DashboardController.php     # Dashboard logic
│   └── Middleware/
│       ├── Authenticate.php            # Check if user is authenticated
│       └── RedirectIfAuthenticated.php # Redirect authenticated users
└── Models/
    └── User.php                        # User model (already exists)

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php              # Master layout
│   ├── auth/
│   │   ├── login.blade.php            # Login form
│   │   ├── register.blade.php         # Registration form
│   │   ├── forgot-password.blade.php  # Forgot password form
│   │   └── reset-password.blade.php   # Password reset form
│   └── dashboard/
│       └── index.blade.php            # Project management dashboard

routes/
└── web.php                            # All routes configured

database/
└── migrations/
    └── 0001_01_01_000000_create_users_table.php  # User & password recovery tables
```

## Setup Instructions

### Step 1: Install Dependencies

```bash
composer install
npm install
```

### Step 2: Environment Configuration

Create a `.env` file from `.env.example`:

```bash
cp .env.example .env
```

Update the following in `.env`:

```
APP_NAME="IT Project Management"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_project_db
DB_USERNAME=root
DB_PASSWORD=

# Email configuration for password resets
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="IT Project Management"
```

### Step 3: Generate Application Key

```bash
php artisan key:generate
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Build Frontend Assets

```bash
npm run build
```

### Step 6: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Usage

### Navigation Flow

1. **Home Page** (`/`) - Click "Register" or "Login" button
2. **Registration** (`/register`) - Create a new account
    - Enter name, email, and password
    - Confirm password
    - Click "Create Account"
    - Automatically redirected to dashboard

3. **Login** (`/login`) - Sign in to existing account
    - Enter email and password
    - Optional: Check "Remember me"
    - Click "Sign In"
    - Redirected to dashboard

4. **Dashboard** (`/dashboard`) - Main project management interface
    - View quick statistics
    - Create new projects using "New Project" button
    - Manage projects (edit/delete)
    - See project progress

5. **Forgot Password** (`/forgot-password`) - Reset password if forgotten
    - Enter email address
    - Click "Send Reset Link"
    - Check email for reset link
    - Click link to reset password
    - Enter new password and confirm

### Authentication Routes

| Route                     | Method | Purpose                       |
| ------------------------- | ------ | ----------------------------- |
| `/login`                  | GET    | Display login form            |
| `/login`                  | POST   | Process login                 |
| `/register`               | GET    | Display registration form     |
| `/register`               | POST   | Process registration          |
| `/forgot-password`        | GET    | Display forgot password form  |
| `/forgot-password`        | POST   | Send reset link email         |
| `/reset-password/{token}` | GET    | Display reset password form   |
| `/reset-password`         | POST   | Process password reset        |
| `/dashboard`              | GET    | Display dashboard (protected) |
| `/logout`                 | POST   | Log out user (protected)      |

## Security Features

✅ **Password Hashing** - Passwords are hashed using bcrypt
✅ **CSRF Protection** - All forms include CSRF tokens
✅ **Email Verification** - Users can verify email addresses
✅ **Remember Token** - Secure "Remember me" functionality
✅ **Password Reset Tokens** - Time-limited password reset links
✅ **Authentication Middleware** - Routes are protected by authentication
✅ **Input Validation** - All inputs are validated

## Customization

### Modify Login/Registration Forms

Edit the view files in `resources/views/auth/`

### Change Dashboard Layout

Edit `resources/views/dashboard/index.blade.php`

### Add New Routes

Add to `routes/web.php` with appropriate middleware

### Modify Validation Rules

Edit the validation rules in `app/Http/Controllers/AuthController.php`

## Email Configuration

For password reset emails to work, configure mail settings in `.env`:

**Option 1: Using Mailtrap (for testing)**

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

**Option 2: Using Gmail**

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

**Option 3: Using Local Development**

```
MAIL_MAILER=log
```

(Emails will be logged instead of sent)

## Database Schema

### Users Table

```sql
- id (primary key)
- name
- email (unique)
- email_verified_at (nullable)
- password
- remember_token
- created_at
- updated_at
```

### Password Reset Tokens Table

```sql
- email (primary key)
- token
- created_at
```

## Troubleshooting

### Issue: "Class not found" errors

**Solution**: Run `composer dump-autoload`

### Issue: Middleware not working

**Solution**: Clear config cache with `php artisan config:clear`

### Issue: Views not found

**Solution**: Check file paths and ensure views are in correct directories

### Issue: Password reset email not received

**Solution**: Check email configuration in `.env` and verify mail service is running

### Issue: Login not working

**Solution**: Ensure database migration ran successfully with `php artisan migrate`

## Future Enhancements

- 🔄 Email verification system
- 👥 Role-based access control (Admin, Manager, Developer)
- 📊 Advanced project statistics and analytics
- 🔔 Notifications system
- 👤 User profile management
- 🔐 Two-factor authentication
- 📱 Mobile app integration
- 🌙 Dark mode theme

## Support

For more information about Laravel authentication:

- [Laravel Authentication Documentation](https://laravel.com/docs/authentication)
- [Laravel Password Reset](https://laravel.com/docs/passwords)

---

**Created with Laravel 11 & Bootstrap 5**
