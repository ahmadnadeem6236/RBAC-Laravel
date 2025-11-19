# Session Login & Remember Me Implementation

## Overview

This application implements a robust session-based authentication system with "Remember Me" functionality that keeps users logged in even after closing their browser.

## How It Works

### 1. Session Storage

Laravel stores session data in files by default (configured in `config/session.php`):

```php
'driver' => env('SESSION_DRIVER', 'file'),
'lifetime' => env('SESSION_LIFETIME', 120), // 120 minutes = 2 hours
'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
```

### 2. Remember Me Token

When a user checks "Remember Me":

-   Laravel creates a `remember_token` in the users table
-   A cookie is set in the browser with this token
-   The cookie lasts for **5 years** (default Laravel behavior)
-   Even if the session expires, the remember token keeps the user logged in

### 3. Authentication Flow

#### Login Process

```php
// In AuthController@login
$remember = $request->boolean('remember'); // Get checkbox value
Auth::attempt($credentials, $remember);    // Attempt login with remember flag
$request->session()->regenerate();         // Regenerate session for security
```

**What happens:**

-   ✅ If `$remember = true`: Creates persistent cookie + remember_token
-   ✅ If `$remember = false`: Creates session-only cookie (expires when browser closes)

#### Register Process

```php
// In AuthController@register
$remember = $request->boolean('remember', true); // Default to true for better UX
Auth::login($user, $remember);
$request->session()->regenerate();
```

**Default behavior:** New users are remembered by default (checkbox is pre-checked)

### 4. Session Security

#### Session Regeneration

```php
$request->session()->regenerate();
```

-   Creates a new session ID
-   Prevents session fixation attacks
-   Called on every login

#### Logout Security

```php
Auth::logout();
$request->session()->invalidate();        // Destroy session data
$request->session()->regenerateToken();   // Regenerate CSRF token
```

## Configuration

### Environment Variables (.env)

```env
SESSION_DRIVER=file           # Store sessions in filesystem
SESSION_LIFETIME=120          # Session expires after 120 minutes
SESSION_EXPIRE_ON_CLOSE=false # Don't expire when browser closes
SESSION_ENCRYPT=false         # Don't encrypt session data (set true for sensitive data)
```

### Session Configuration (config/session.php)

Key settings:

```php
'lifetime' => 120,                    // 2 hours
'expire_on_close' => false,           // Keep session alive after browser close
'encrypt' => false,                   // Encryption (enable for sensitive data)
'files' => storage_path('framework/sessions'), // Storage location
'cookie' => env('SESSION_COOKIE', 'laravel_session'),
'path' => '/',
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE', false),  // HTTPS only
'http_only' => true,                  // Prevent JavaScript access (XSS protection)
'same_site' => 'lax',                 // CSRF protection
```

## User Experience

### Login Form Features

1. **Email Field**

    - Retains value on failed login attempts
    - Email validation

2. **Password Field**

    - Minimum 6 characters
    - Never pre-filled for security

3. **Remember Me Checkbox**

    - Optional (unchecked by default)
    - Label: "Remember Me (Stay logged in)"
    - User choice: stay logged in or session-only

4. **Error Messages**
    - Displayed inline under each field
    - Preserves form state on error

### Register Form Features

1. **All Fields Validated**

    - Name: Required, max 255 chars
    - Email: Required, unique, valid email format
    - Password: Min 6 characters

2. **Remember Me Checkbox**

    - Pre-checked by default
    - Better UX for new users

3. **Error Handling**
    - Shows validation errors inline
    - Retains form data (except password)

## Testing Session Login

### Test Remember Me Functionality

1. **Login with "Remember Me" checked:**

    ```
    - Login as user1@example.com
    - Check "Remember Me"
    - Close browser completely
    - Reopen browser and visit http://localhost:8000
    - ✅ Should still be logged in (redirects to dashboard)
    ```

2. **Login without "Remember Me":**
    ```
    - Login as user2@example.com
    - Leave "Remember Me" unchecked
    - Close browser completely
    - Reopen browser and visit http://localhost:8000
    - ✅ Should be logged out (redirects to login)
    ```

### Check Session Data

You can inspect session data using Laravel Tinker:

```bash
php artisan tinker

# Check if user is authenticated
Auth::check()

# Get current user
Auth::user()

# Check remember token
Auth::user()->remember_token
```

## Advanced: Session Database Driver

For production or multiple servers, use database sessions:

### 1. Create Session Table

```bash
php artisan session:table
php artisan migrate
```

### 2. Update .env

```env
SESSION_DRIVER=database
```

### Benefits:

-   ✅ Works across multiple servers
-   ✅ Better for load-balanced apps
-   ✅ Can query/manage sessions with SQL

## Security Best Practices

### ✅ Implemented

1. **Session Regeneration** - Prevents session fixation
2. **CSRF Protection** - All forms use `@csrf` token
3. **HTTP Only Cookies** - JavaScript cannot access session cookies
4. **Password Hashing** - bcrypt for all passwords
5. **Input Validation** - All user input validated
6. **Remember Token** - Secure persistent authentication

### 🔐 Production Recommendations

1. **Enable HTTPS:**

    ```env
    SESSION_SECURE_COOKIE=true
    ```

2. **Enable Session Encryption:**

    ```env
    SESSION_ENCRYPT=true
    ```

3. **Use Database Driver:**

    ```env
    SESSION_DRIVER=database
    ```

4. **Shorter Session Lifetime:**

    ```env
    SESSION_LIFETIME=60  # 1 hour instead of 2
    ```

5. **Set Proper Domain:**
    ```env
    SESSION_DOMAIN=.yourdomain.com
    ```

## Troubleshooting

### Sessions Not Persisting

1. Check storage permissions:

    ```bash
    chmod -R 775 storage/framework/sessions
    ```

2. Clear session cache:

    ```bash
    php artisan session:clear
    php artisan cache:clear
    ```

3. Check .env configuration:
    ```bash
    php artisan config:cache
    ```

### Remember Me Not Working

1. Check users table has `remember_token` column:

    ```bash
    php artisan tinker
    Schema::hasColumn('users', 'remember_token')
    ```

2. Clear cookies in browser and try again

3. Check if `Auth::attempt($credentials, true)` is being called

## Database Schema

The `users` table includes the remember_token column:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();  // ← This stores the remember token
    $table->timestamps();
});
```

## Session Lifecycle

```
1. User visits site
   ↓
2. Session ID created (stored in cookie)
   ↓
3. User logs in with "Remember Me"
   ↓
4. Auth::attempt() creates:
   - Session data (who is logged in)
   - Remember token (in database)
   - Remember cookie (in browser, 5 years)
   ↓
5. User closes browser
   ↓
6. User returns to site
   ↓
7. Laravel checks:
   - Is there a valid session? (If within 120 min)
   - Is there a remember cookie? (If yes, re-login automatically)
   ↓
8. User is authenticated ✅
```

## Summary

✅ **Login Form** - Optional "Remember Me" checkbox
✅ **Register Form** - "Remember Me" checked by default  
✅ **Session Security** - Regeneration on auth state changes
✅ **Persistent Login** - Remember tokens last 5 years
✅ **Session Lifetime** - 120 minutes (configurable)
✅ **CSRF Protection** - All forms protected
✅ **Input Validation** - All fields validated with error display
✅ **Secure Logout** - Invalidates session and tokens

The system is production-ready with proper session management! 🎉
