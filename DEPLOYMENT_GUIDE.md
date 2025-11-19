# 🚀 Laravel RBAC - Render Deployment Guide

This guide will walk you through deploying your Laravel application to Render.com step by step.

## 📋 Prerequisites

- [ ] GitHub account
- [ ] Render account (free tier is fine)
- [ ] Git installed on your computer
- [ ] Your Laravel app ready to deploy

---

## 🔧 Step 1: Prepare Your Repository

### 1.1 Check Git Status

```bash
git status
```

### 1.2 Add All Files to Git

```bash
git add .
```

### 1.3 Commit Your Changes

```bash
git commit -m "Prepare for Render deployment"
```

### 1.4 Push to GitHub

If you haven't already, create a GitHub repository and push your code:

```bash
# First time setup (replace with your repo URL)
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git branch -M main
git push -u origin main

# Or if already connected:
git push
```

---

## 🌐 Step 2: Set Up Render Account

### 2.1 Sign Up / Log In

1. Go to [https://render.com](https://render.com)
2. Sign up or log in
3. Connect your GitHub account if you haven't already

---

## 🗄️ Step 3: Create PostgreSQL Database

### 3.1 Create New PostgreSQL Service

1. In Render Dashboard, click **"New +"** button
2. Select **"PostgreSQL"**
3. Fill in the details:
   - **Name**: `laravel-rbac-db`
   - **Database**: `rbac_db`
   - **User**: `rbac_user`
   - **Region**: `Oregon (US West)` (or your preferred region)
   - **Plan**: `Free`

4. Click **"Create Database"**

### 3.2 Wait for Database Creation

Wait 1-2 minutes for the database to be created. You'll see a green "Available" status when ready.

### 3.3 Save Database Credentials (Important!)

Once created, go to the database's **"Info"** tab and note down:
- **Internal Database URL** (you'll need this)
- **Hostname**
- **Port**
- **Database Name**
- **Username**
- **Password**

---

## 🔑 Step 4: Generate Laravel Application Key

### 4.1 Generate Key Locally

In your project directory, run:

```bash
php artisan key:generate --show
```

### 4.2 Copy the Output

Copy the entire output (it should look like `base64:abcdefgh123456789...`)

**Save this somewhere safe - you'll need it in Step 6!**

---

## 🌟 Step 5: Create Web Service

### 5.1 Create New Web Service

1. In Render Dashboard, click **"New +"** button
2. Select **"Blueprint"**
3. Select **"Connect a repository"**
4. Choose your GitHub repository
5. Give Render permission to access it if prompted

---

## 🎯 Step 6: Deploy Using Blueprint

### 6.1 Initial Deployment

1. Render will detect your `render.yaml` file
2. Click **"Apply"** to create services from the blueprint
3. You'll see:
   - **laravel-rbac-db** (PostgreSQL database)
   - **laravel-rbac** (Web service)

### 6.2 Configure Environment Variables

⚠️ **IMPORTANT**: Before the deployment succeeds, you need to set the `APP_KEY`:

1. Click on your **laravel-rbac** web service
2. Go to **"Environment"** tab
3. Find **"APP_KEY"** variable
4. Click **"Edit"** and paste the key you generated in Step 4.1
5. Click **"Save Changes"**

### 6.3 Update APP_URL

1. Once deployed, you'll get a URL like `https://laravel-rbac-xxxx.onrender.com`
2. Go back to **"Environment"** tab
3. Update **"APP_URL"** with your actual Render URL
4. Click **"Save Changes"**

---

## ⏳ Step 7: Wait for Deployment

### 7.1 Monitor Build Process

1. Go to the **"Logs"** tab
2. Watch the build process (it may take 5-10 minutes)
3. Look for these messages:
   - ✅ `Building image`
   - ✅ `Running migrations`
   - ✅ `Seeding database`
   - ✅ `Build completed successfully`

### 7.2 Check for Errors

If you see errors:
- Red text indicates failures
- Check database connection
- Verify environment variables
- Review logs for specific issues

---

## 🎉 Step 8: Test Your Deployment

### 8.1 Access Your Application

1. Click on the URL at the top of your service page
2. You should see your Laravel application
3. Try accessing `/login`

### 8.2 Test Login

Default test users (if using the seeder):
- **Admin**: 
  - Email: `admin@example.com`
  - Password: `password`
  
- **Manager**:
  - Email: `manager@example.com`
  - Password: `password`

- **User**:
  - Email: `user@example.com`
  - Password: `password`

---

## 🔧 Step 9: Post-Deployment Configuration

### 9.1 Disable Seeder (Optional)

After first successful deployment, you may want to disable the seeder to prevent duplicate entries:

1. Edit `start.sh` (line 16)
2. Comment out or remove the seeder line:
   ```bash
   # php artisan db:seed --class=RoleUserSeeder --force
   ```
3. Commit and push:
   ```bash
   git add start.sh
   git commit -m "Disable seeder after initial deployment"
   git push
   ```

### 9.2 Set APP_DEBUG to False

For production security:

1. Go to **"Environment"** tab in Render
2. Set **"APP_DEBUG"** to `false`
3. Click **"Save Changes"**

---

## 📝 Common Issues & Solutions

### Issue 1: "Key not found" Error

**Solution**: Make sure you've set the `APP_KEY` environment variable with the key generated in Step 4.

### Issue 2: Database Connection Failed

**Solution**: 
- Check that the database service is running (green status)
- Verify database credentials in environment variables
- Ensure both services are in the same region

### Issue 3: 502 Bad Gateway

**Solution**: 
- Wait a few minutes for services to fully start
- Check logs for PHP-FPM or Nginx errors
- Verify health check path is accessible

### Issue 4: Migration Errors

**Solution**:
- Check database is created and accessible
- Verify `DB_CONNECTION` is set to `pgsql`
- Review migration files for PostgreSQL compatibility

### Issue 5: Slow First Load

**Solution**: This is normal on free tier! Render spins down free services after inactivity. First load after idle will take 30-60 seconds.

---

## 🔄 Updating Your Application

### Method 1: Git Push (Automatic)

```bash
# Make your changes
git add .
git commit -m "Your update message"
git push
```

Render will automatically detect changes and redeploy!

### Method 2: Manual Deploy

1. Go to your service in Render Dashboard
2. Click **"Manual Deploy"** button
3. Select **"Clear build cache & deploy"**

---

## 🎛️ Useful Render Commands

### View Logs in Real-Time

In Render Dashboard:
- Go to your service
- Click **"Logs"** tab
- Logs update automatically

### Restart Service

In Render Dashboard:
- Go to your service
- Click **"Manual Deploy"** → **"Deploy latest commit"**

### Access Shell (Paid Plans Only)

In Render Dashboard:
- Go to your service
- Click **"Shell"** tab
- Run artisan commands directly

---

## 🎯 Quick Reference

### Your Service URLs

- **Web App**: `https://laravel-rbac-xxxx.onrender.com`
- **Login Page**: `https://laravel-rbac-xxxx.onrender.com/login`
- **Dashboard**: `https://laravel-rbac-xxxx.onrender.com/dashboard`

### Important Files

- `render.yaml` - Render configuration
- `Dockerfile` - Docker image definition
- `start.sh` - Container startup script
- `nginx.conf` - Web server configuration

### Render Dashboard Links

- **Dashboard**: https://dashboard.render.com
- **Services**: https://dashboard.render.com/services
- **Documentation**: https://render.com/docs

---

## 📚 Additional Resources

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Docker Documentation](https://docs.docker.com)

---

## ✅ Deployment Checklist

Use this checklist to ensure everything is set up correctly:

- [ ] Code pushed to GitHub
- [ ] PostgreSQL database created on Render
- [ ] Web service created via Blueprint
- [ ] APP_KEY environment variable set
- [ ] APP_URL environment variable updated
- [ ] First deployment successful
- [ ] Login page accessible
- [ ] Test user can log in
- [ ] Database migrations ran successfully
- [ ] All environment variables configured
- [ ] APP_DEBUG set to false (production)

---

## 🆘 Need Help?

If you encounter issues:

1. Check the **Logs** tab in Render Dashboard
2. Review this guide's troubleshooting section
3. Check Render's documentation
4. Review Laravel logs (if accessible)

---

**🎊 Congratulations! Your Laravel RBAC application should now be live on Render!**

Last Updated: November 19, 2025

