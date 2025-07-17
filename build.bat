@echo off
REM Hostinger Deployment Build Script for Windows
REM This script prepares the application for Hostinger deployment

echo 🚀 Building Dental Practice Pay Survey for Hostinger...

REM Create build directory
if exist build rmdir /s /q build
mkdir build

REM Copy all necessary files to build directory
echo 📁 Copying application files...
copy index.html build\
copy styles.css build\
copy script.js build\
copy submit.php build\
copy averages.php build\
copy database.sql build\
copy config.hostinger.php build\config.php

REM Create deployment instructions
echo 📋 Creating deployment instructions...
(
echo HOSTINGER DEPLOYMENT INSTRUCTIONS
echo ==================================
echo.
echo 1. DATABASE SETUP:
echo    - Create MySQL database in Hostinger control panel
echo    - Import database.sql via phpMyAdmin
echo    - Note your database credentials
echo.
echo 2. CONFIGURATION:
echo    - Edit config.php with your actual database credentials:
echo      * DB_HOST ^(usually 'localhost'^)
echo      * DB_NAME ^(your database name^)
echo      * DB_USER ^(your database username^)
echo      * DB_PASS ^(your database password^)
echo.
echo 3. FILE UPLOAD:
echo    - Upload all files to your public_html directory
echo    - Set permissions: 644 for files, 755 for directories
echo.
echo 4. TESTING:
echo    - Visit your domain to test the application
echo    - Submit a test survey
echo    - Check if charts display properly
echo.
echo 5. TROUBLESHOOTING:
echo    - Check PHP error logs if application doesn't work
echo    - Verify database connection in config.php
echo    - Ensure all files are uploaded correctly
echo.
echo Files included in this build:
echo - index.html ^(main application^)
echo - styles.css ^(styling^)
echo - script.js ^(frontend functionality^)
echo - submit.php ^(form handler^)
echo - averages.php ^(data API^)
echo - database.sql ^(database schema^)
echo - config.php ^(configuration - UPDATE WITH YOUR CREDENTIALS^)
) > build\DEPLOYMENT_INSTRUCTIONS.txt

echo.
echo ✅ Build complete! Files are ready in the 'build' directory.
echo 📦 Upload the contents of the 'build' directory to your Hostinger public_html folder.
echo 🔧 Don't forget to update config.php with your actual database credentials!
echo.
pause
