#!/bin/bash

# Hostinger Deployment Build Script
# This script prepares the application for Hostinger deployment

echo "🚀 Building Dental Practice Pay Survey for Hostinger..."

# Create build directory
mkdir -p build

# Copy all necessary files to build directory
echo "📁 Copying application files..."
cp index.html build/
cp styles.css build/
cp script.js build/
cp submit.php build/
cp averages.php build/
cp database.sql build/
cp config.hostinger.php build/config.php

# Create deployment instructions
echo "📋 Creating deployment instructions..."
cat > build/DEPLOYMENT_INSTRUCTIONS.txt << 'EOF'
HOSTINGER DEPLOYMENT INSTRUCTIONS
==================================

1. DATABASE SETUP:
   - Create MySQL database in Hostinger control panel
   - Import database.sql via phpMyAdmin
   - Note your database credentials

2. CONFIGURATION:
   - Edit config.php with your actual database credentials:
     * DB_HOST (usually 'localhost')
     * DB_NAME (your database name)
     * DB_USER (your database username)
     * DB_PASS (your database password)

3. FILE UPLOAD:
   - Upload all files to your public_html directory
   - Set permissions: 644 for files, 755 for directories

4. TESTING:
   - Visit your domain to test the application
   - Submit a test survey
   - Check if charts display properly

5. TROUBLESHOOTING:
   - Check PHP error logs if application doesn't work
   - Verify database connection in config.php
   - Ensure all files are uploaded correctly

Files included in this build:
- index.html (main application)
- styles.css (styling)
- script.js (frontend functionality)
- submit.php (form handler)
- averages.php (data API)
- database.sql (database schema)
- config.php (configuration - UPDATE WITH YOUR CREDENTIALS)
EOF

echo "✅ Build complete! Files are ready in the 'build' directory."
echo "📦 Upload the contents of the 'build' directory to your Hostinger public_html folder."
echo "🔧 Don't forget to update config.php with your actual database credentials!"
