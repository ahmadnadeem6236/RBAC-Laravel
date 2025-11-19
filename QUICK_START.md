# Quick Start Guide

## 🚀 Your Laravel RBAC System is Ready!

The development server is running at: **http://localhost:8000**

Visit the root URL and you'll be automatically redirected to login!

## 🗄️ Database Setup

This application uses **different databases for different environments**:

- **Local Development**: SQLite (already configured, no setup needed!)
  - File: `database/database.sqlite`
  - Zero configuration required ✅
  
- **Production (Render)**: PostgreSQL
  - Automatically configured via `render.yaml`
  - Managed database with better performance

**You don't need to configure anything** - it just works! 🎉

## 📋 Test the System

### Step 1: Login with Test Users

Visit: `http://localhost:8000` (or `http://localhost:8000/login`)

Try each user to see different access levels:

#### User 1 - Project Access Only
```
Email: user1@example.com
Password: password
```
✅ Can access: Dashboard, Projects

#### User 2 - Manager
```
Email: user2@example.com
Password: password
```
✅ Can access: Dashboard, Projects, User Management

#### User 3 - Admin
```
Email: user3@example.com
Password: password
```
✅ Can access: Dashboard, Projects, User Management, Admin Panel

### Step 2: Explore Features

After logging in, notice:

1. **Navigation Bar**: Links shown/hidden based on your role
2. **Dashboard**: Shows what you have access to
3. **Access Control**: Try accessing URLs directly (e.g., `/admin`) with different users
4. **Remember Me Feature**: 
   - Check "Remember Me" when logging in
   - Close browser completely and reopen
   - Visit http://localhost:8000 - you'll still be logged in!
5. **Form Validation**: Try logging in with wrong credentials to see error messages

## 🧪 Testing Access Control

Try accessing these URLs after logging in:

| URL | User1 | User2 | User3 |
|-----|-------|-------|-------|
| /dashboard | ✅ | ✅ | ✅ |
| /projects | ✅ | ✅ | ✅ |
| /users | ❌ 403 | ✅ | ✅ |
| /admin | ❌ 403 | ❌ 403 | ✅ |

## 🔄 Reset Database (if needed)

```bash
php artisan migrate:fresh --force
php artisan db:seed --class=RoleUserSeeder --force
```

## ⏹️ Stop the Server

Press `Ctrl + C` in the terminal where the server is running.

## 📝 Register New Users

1. Visit: `http://localhost:8000/register`
2. Create a new account
3. Note: New users won't have any roles by default
4. To assign roles, use Laravel Tinker:

```bash
php artisan tinker

# Assign a role to user
$user = User::find(4); // or User::where('email', 'newemail@example.com')->first();
$role = Role::where('name', 'project_access')->first();
$user->roles()->attach($role);
exit
```

## 🎨 Customize

- **Add more roles**: Edit `RoleUserSeeder.php`
- **Change UI**: Edit Blade files in `resources/views/`
- **Add features**: Create new controllers and routes
- **Styling**: Update CSS in `resources/views/layouts/app.blade.php`

## 📚 File Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php
│   ├── ProjectController.php
│   ├── UserManagementController.php
│   └── AdminController.php
├── Models/
│   ├── Role.php
│   └── User.php

database/
├── migrations/
│   ├── *_create_roles_table.php
│   └── *_create_role_user_table.php
└── seeders/
    └── RoleUserSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── dashboard.blade.php
├── projects/index.blade.php
├── users/index.blade.php
└── admin/index.blade.php

routes/
└── web.php
```

## 🐛 Troubleshooting

### Port Already in Use
```bash
php artisan serve --port=8001
```

### Database Issues
```bash
# Check database connection
php artisan migrate:status

# Reset everything
php artisan migrate:fresh --force --seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## ✅ What's Implemented

- ✅ Database schema (roles, role_user pivot table)
- ✅ Role and User models with relationships
- ✅ Authentication system (login, register, logout)
- ✅ **"Remember Me" checkbox** on login and register forms
- ✅ Role-based access control
- ✅ Conditional UI rendering
- ✅ Protected routes
- ✅ Test users with different roles
- ✅ Session persistence with remember tokens
- ✅ CSRF protection
- ✅ Password hashing
- ✅ Form validation with error display
- ✅ Input persistence on validation errors

## 📚 Additional Documentation

- **[README.md](README.md)** - Complete system documentation
- **[SESSION_LOGIN.md](SESSION_LOGIN.md)** - Detailed session & authentication guide
- **[DEPLOYMENT_RENDER.md](DEPLOYMENT_RENDER.md)** - Deploy to Render (full guide)
- **[DEPLOY_QUICK.md](DEPLOY_QUICK.md)** - Quick deployment guide
- **[DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)** - Deployment files explained

## 🌐 Deploy to Production

Want to deploy this to Render? It's super easy!

```bash
# 1. Push to GitHub
git init
git add .
git commit -m "Initial commit"
git remote add origin YOUR_GITHUB_REPO_URL
git push -u origin main

# 2. Go to Render Dashboard
# https://dashboard.render.com/

# 3. New + → Blueprint
# Connect your repo - Render auto-detects render.yaml!

# 4. Done! Your app is live in 5 minutes! 🎉
```

See [DEPLOY_QUICK.md](DEPLOY_QUICK.md) for detailed steps.

Enjoy your RBAC system! 🎉

