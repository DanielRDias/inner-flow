# Inner Flow - Quick Start Guide

## Quick Start (Recommended - Using Local by Flywheel)

The easiest way to run this WordPress site locally:

### Option 1: Local by Flywheel (Easiest)

1. **Download Local by Flywheel**
   - Visit: https://localwp.com/
   - Download and install (it's free)

2. **Create New Site**
   - Open Local
   - Click "Create a new site"
   - Site name: "Inner Flow"
   - Environment: PHP 8.0+, MySQL 8.0+
   - WordPress credentials:
     - Username: admin
     - Password: admin
     - Email: your@email.com

3. **Replace Files**
   - After site is created, find the site folder
   - Replace the `wp-content` folder with our custom `wp-content`
   - Or copy our theme and plugin:
     ```
     Copy wp-content/themes/inner-flow → [Local Site]/app/public/wp-content/themes/
     Copy wp-content/plugins/inner-flow-events → [Local Site]/app/public/wp-content/plugins/
     ```

4. **Activate Theme & Plugin**
   - Click "WP Admin" in Local
   - Go to Appearance > Themes → Activate "Inner Flow"
   - Go to Plugins → Activate "Inner Flow Hiking Events"

5. **Install Required Plugins**
   - Go to Plugins > Add New
   - Search and install:
     - "Polylang" (for multilingual support)
     - "Nextend Social Login" (for Google login)
   - Activate both plugins

6. **Configure**
   - Go to Languages → Add Portuguese (pt_BR) and English
   - Configure Google Maps API key in theme functions.php
   - Configure Google OAuth in Nextend Social Login settings

---

## Option 2: Using XAMPP

1. **Install XAMPP**
   - Download from: https://www.apachefriends.org/
   - Install with Apache and MySQL

2. **Move Project**
   ```powershell
   # Move the inner-flow folder to XAMPP htdocs
   Move-Item c:\Users\Danie\projects\inner-flow C:\xampp\htdocs\
   ```

3. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

4. **Create Database**
   - Visit: http://localhost/phpmyadmin
   - Create database: `inner_flow`
   - Import or let WordPress create tables

5. **Install WordPress**
   - Visit: http://localhost/inner-flow
   - Follow WordPress installation wizard
   - Use database credentials:
     - Database: inner_flow
     - Username: root
     - Password: (leave empty)
     - Host: localhost

6. **Activate Theme & Plugins** (same as Option 1, steps 4-6)

---

## Option 3: Using Docker (For Developers)

1. **Install Docker Desktop**
   - Download from: https://www.docker.com/products/docker-desktop
   - Install and start Docker

2. **Start Services**
   ```powershell
   cd c:\Users\Danie\projects\inner-flow
   docker-compose up -d
   ```

3. **Access Site**
   - WordPress: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

4. **Complete Installation**
   - Visit http://localhost:8080
   - Follow WordPress setup wizard
   - Database credentials:
     - Database: inner_flow
     - Username: wordpress
     - Password: wordpress
     - Host: db

5. **Activate Theme & Plugins** (same as Option 1, steps 4-6)

---

## Option 4: Using PowerShell Scripts (Advanced)

1. **Install Requirements**
   ```powershell
   # Run as Administrator
   .\install-requirements.ps1
   ```

2. **Setup Database**
   ```powershell
   .\setup-database.ps1
   ```

3. **Start Server**
   ```powershell
   .\start-server.ps1
   ```

4. **Access Site**
   - Visit: http://localhost:8080
   - Complete WordPress installation

---

## After Setup - Configuration

### 1. Configure Languages (Polylang)

1. Go to **Languages** in WordPress admin
2. Add languages:
   - Portuguese (Português - pt_BR)
   - English (en_US)
3. Set default language
4. Go to **Strings translations** to translate custom strings

### 2. Configure Google Maps API

1. Get API key from: https://console.cloud.google.com/
2. Enable these APIs:
   - Maps JavaScript API
   - Places API
   - Geocoding API
3. Edit `wp-content/themes/inner-flow/functions.php`
4. Line 28: Replace `YOUR_API_KEY` with your actual key

### 3. Configure Google OAuth

1. Get OAuth credentials from Google Cloud Console
2. Create OAuth 2.0 Client ID
3. In WordPress:
   - Go to **Settings > Nextend Social Login**
   - Configure Google provider with your credentials

### 4. Create Your First Hiking Event

1. Go to **Hiking Events > Add New**
2. Enter:
   - Title: "Serra da Estrela Trek"
   - Description: Your event details
   - Date & Time
   - Location: Use map search
3. Add route and stop points
4. Publish!

---

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify database credentials in wp-config.php
- Ensure database exists

### White Screen
- Enable WP_DEBUG in wp-config.php
- Check error logs

### Theme Not Showing
- Ensure theme files are in `wp-content/themes/inner-flow/`
- Check file permissions

### Plugin Not Working
- Ensure plugin files are in `wp-content/plugins/inner-flow-events/`
- Activate plugin in WordPress admin

---

## Site URLs

After setup, your site will be available at:

- **With Local**: As shown in Local app (usually http://inner-flow.local)
- **With XAMPP**: http://localhost/inner-flow
- **With Docker**: http://localhost:8080
- **With PHP**: http://localhost:8080

---

## Default Credentials

### WordPress Admin
- Username: admin
- Password: (set during installation)

### Database (Docker)
- Database: inner_flow
- User: wordpress
- Password: wordpress

### Database (XAMPP)
- Database: inner_flow  
- User: root
- Password: (empty)

---

## Next Steps

1. ✅ Install WordPress environment (Local/XAMPP/Docker)
2. ✅ Activate Inner Flow theme
3. ✅ Activate Inner Flow Events plugin
4. ✅ Install Polylang and Nextend Social Login
5. ✅ Configure Google Maps API
6. ✅ Configure Google OAuth
7. ✅ Add Portuguese and English languages
8. ✅ Create your first hiking event
9. ✅ Test user registration

Enjoy your Inner Flow hiking events website! 🏔️
