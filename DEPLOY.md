# Production Deployment Guide

## Quick Deploy to Hostinger

### Step 1: Repository Setup
1. Push this repository to GitHub/GitLab
2. Ensure `.env` and `config.php` are in `.gitignore`

### Step 2: Hostinger Database Setup
1. Log into Hostinger control panel
2. Go to "Databases" → "MySQL Databases"
3. Create new database: `u395558639_dentalpay`
4. Create database user with full privileges
5. Note: hostname, database name, username, password

### Step 3: Deploy via Git (Recommended)
1. In Hostinger control panel, go to "Git"
2. Click "Create new repository"
3. Enter your repository URL
4. Set branch to `main`
5. Set deployment path to `public_html`
6. Enable automatic deployment

### Step 4: Configure Environment
Create `config.php` in your deployment directory:

```php
<?php
// Production Database Configuration
define('DB_HOST', 'mysql.hostinger.com');
define('DB_NAME', 'u395558639_dentalpay');   // Your actual DB name
define('DB_USER', 'admin');                  // Your actual DB user
define('DB_PASS', 'your_secure_password');   // Your actual DB password
define('DB_CHARSET', 'utf8mb4');

// Production Application Configuration
define('APP_ENV', 'production');
define('APP_DEBUG', false);

// Include the rest of the functions from config.example.php
?>
```

### Step 5: Import Database Schema
1. Access phpMyAdmin from Hostinger control panel
2. Select your database
3. Go to "Import" tab
4. Upload `database.sql` file
5. Click "Go" to execute

### Step 6: Test Deployment
1. Visit your domain
2. Test form submission
3. Check that charts load with empty data message
4. Submit a test survey to verify database connection

### Step 7: SSL Certificate (Optional but Recommended)
1. In Hostinger control panel, go to "SSL"
2. Enable "Force HTTPS redirect"

## Alternative: FTP Deployment

If Git deployment is not available:

1. Use FTP client (FileZilla, WinSCP)
2. Upload all files except:
   - `.git/` directory
   - `config.php` (create manually)
   - `.env` files
   - `README.md` (optional)
3. Set file permissions: 644 for files, 755 for directories
4. Create `config.php` with your database credentials
5. Import database schema via phpMyAdmin

## Environment Variables Alternative

For hosts that support environment variables:

```bash
# Set these in your hosting control panel
DB_HOST=mysql.hostinger.com
DB_NAME=u395558639_dentalpay
DB_USER=admin
DB_PASS=your_secure_password
APP_ENV=production
APP_DEBUG=false
```

## Security Checklist

- [ ] Database credentials not in version control
- [ ] `APP_DEBUG` set to `false` in production
- [ ] SSL certificate enabled
- [ ] Database user has minimal required permissions
- [ ] File permissions set correctly (644/755)
- [ ] Error logging enabled but not publicly accessible

## Troubleshooting

### Common Issues:
1. **"An error occurred while processing your submission"**
   - Upload test-db.php to check database connection
   - Verify config.php has correct credentials
   - Check if database.sql was imported properly
   - Check PHP error logs in public_html/error_logs/

2. **Database Connection Error**: Check credentials in config.php
3. **500 Internal Server Error**: Check PHP error logs
4. **Charts Not Loading**: Verify database has data or shows empty state
5. **Form Submission Fails**: Check database table structure

### Debug Steps:
1. **Test Database Connection:**
   - Upload test-db.php to your public_html directory
   - Visit yoursite.com/test-db.php
   - Check connection status and table structure
   - Delete test-db.php after testing

2. **Check PHP Error Logs:**
   - Look in public_html/error_logs/ directory
   - Check for any PHP errors or database connection issues

3. **Verify Database Setup:**
   - Ensure database.sql was imported via phpMyAdmin
   - Check that all tables exist
   - Verify database user has proper permissions

### Support Resources:
- Hostinger Documentation: https://support.hostinger.com
- PHP Error Logs: Usually in `public_html/error_logs/`
- Database Access: phpMyAdmin in control panel
