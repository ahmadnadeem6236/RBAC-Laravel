# Laravel RBAC (Role-Based Access Control) System

A complete Laravel application implementing Role-Based Access Control with conditional rendering and session persistence.

## Features

- **Role-Based Access Control**: Three roles (project_access, manager, admin) with different permission levels
- **User Authentication**: Login, register, and logout functionality with persistent sessions
- **Remember Me**: Optional "Remember Me" checkbox to stay logged in for 5 years
- **Session Management**: Secure session handling with regeneration and proper logout
- **Conditional Rendering**: Navigation and dashboard elements shown/hidden based on user roles
- **Secure**: CSRF protection, password hashing, and session regeneration
- **Form Validation**: Real-time error display with input persistence

## Database Configuration

This application is configured to use different databases for different environments:

### 🗄️ Local Development → SQLite

- **Default database**: SQLite (file-based, zero configuration)
- **Location**: `database/database.sqlite`
- **No setup required**: Works out of the box!
- **Perfect for**: Local development and testing

### 🐘 Production → PostgreSQL

- **Database**: PostgreSQL (via Render's managed database)
- **Configuration**: Automatically set via `render.yaml`
- **Environment variable**: `DB_CONNECTION=pgsql`
- **Perfect for**: Production deployments with better performance and features

**How it works**: The `config/database.php` defaults to SQLite when no `DB_CONNECTION` environment variable is set. In production (Render), the `render.yaml` file automatically configures PostgreSQL.

## Installation

### Local Development

The project is already set up with SQLite! If you need to reset:

```bash
# Fresh migration (if needed)
php artisan migrate:fresh --force

# Seed database with test users
php artisan db:seed --class=RoleUserSeeder --force
```

### Deployment to Render

**Quick Deploy:**

```bash
git init
git add .
git commit -m "Deploy to Render"
git remote add origin YOUR_REPO_URL
git push -u origin main

# Then connect to Render - it auto-detects render.yaml!
```

## Usage

### Start the Development Server

```bash
php artisan serve
```

Then visit: `http://localhost:8000/login`

### Test Credentials

| Email             | Password | Role           | Access                           |
| ----------------- | -------- | -------------- | -------------------------------- |
| user1@example.com | password | project_access | Projects only                    |
| user2@example.com | password | manager        | Projects + User Management       |
| user3@example.com | password | admin          | Full admin access (all features) |

## Project Structure

### Database

- **roles**: Stores role names (project_access, manager, admin)
- **role_user**: Pivot table for many-to-many relationship
- **users**: Standard Laravel users table

### Models

- **Role.php**: Role model with belongsToMany relationship to Users
- **User.php**: Extended with `roles()` relationship and `hasRole()` helper method

### Controllers

- **AuthController**: Handles login, register, logout, and dashboard
- **ProjectController**: Projects page (accessible by project_access, manager, admin)
- **UserManagementController**: User management (accessible by manager, admin)
- **AdminController**: Admin panel (accessible by admin only)

### Views

All views use conditional rendering with `@if` directives:

- **layouts/app.blade.php**: Main layout with role-based navigation
- **auth/login.blade.php**: Login form
- **auth/register.blade.php**: Registration form
- **dashboard.blade.php**: User dashboard showing available features
- **projects/index.blade.php**: Projects page
- **users/index.blade.php**: User management page
- **admin/index.blade.php**: Admin panel

### Routes

```php
// Root route
GET  /          - Redirects to dashboard (if authenticated) or login (if not)

// Public routes
GET  /login     - Login form
POST /login     - Process login
GET  /register  - Registration form
POST /register  - Process registration

// Protected routes (require authentication)
GET  /dashboard - User dashboard
POST /logout    - Logout user
GET  /projects  - Projects page
GET  /users     - User management
GET  /admin     - Admin panel
```

## Role Permissions

### project_access

- ✅ Dashboard
- ✅ Projects

### manager

- ✅ Dashboard
- ✅ Projects
- ✅ User Management

### admin

- ✅ Dashboard
- ✅ Projects
- ✅ User Management
- ✅ Admin Panel

## Security Features

- Password hashing with bcrypt
- CSRF token protection on all forms
- Session regeneration on login/logout
- Optional persistent sessions with "Remember Me" checkbox
- Remember tokens for long-term authentication (5 years)
- HTTP-only cookies (XSS protection)
- Role-based authorization checks in controllers
- Conditional UI rendering based on roles
- Input validation with error display

## Session Configuration

The application uses file-based sessions with the following configuration (in .env):

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_EXPIRE_ON_CLOSE=false
```

### Remember Me Feature

- **Login Form**: Optional "Remember Me" checkbox (unchecked by default)
- **Register Form**: "Remember Me" checkbox (checked by default for better UX)
- **How it works**: When checked, creates a remember token that keeps users logged in for 5 years
- **Session lifetime**: Without "Remember Me", sessions expire after 120 minutes or when browser closes

For detailed information, see [SESSION_LOGIN.md](SESSION_LOGIN.md)

## Creating New Roles

To add a new role:

1. Create the role in database:

```php
Role::create(['name' => 'new_role']);
```

2. Assign to user:

```php
$user->roles()->attach($roleId);
```

3. Check in views:

```blade
@if(auth()->user()->hasRole('new_role'))
    <!-- Content -->
@endif
```

4. Check in controllers:

```php
if (!auth()->user()->hasRole('new_role')) {
    abort(403, 'Access Denied');
}
```

## Deployment Files

This project includes complete deployment configuration for Render:

| File            | Purpose                        |
| --------------- | ------------------------------ |
| `render.yaml`   | Render Blueprint configuration |
| `build.sh`      | Automated build script         |
| `Procfile`      | Application start command      |
| `.renderignore` | Deployment exclusions          |

## Development

This is a simple, production-ready RBAC implementation suitable for small to medium applications. For larger applications, consider using packages like:

- Laravel Sanctum (for API authentication)
- Spatie Laravel Permission (advanced permissions)
- Laravel Jetstream (full authentication scaffolding)

## License

Open-source software licensed under the MIT license.
