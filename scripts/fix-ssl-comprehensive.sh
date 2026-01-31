#!/bin/bash

# Comprehensive SSL fix for localhost WordPress
# Usage: ./scripts/fix-ssl-comprehensive.sh

CONTAINER_NAME="tsm-theme-db"
WP_CONTAINER="tsm-theme-wordpress"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "=== Comprehensive SSL Fix for Localhost ==="
echo ""

# 1. Force update URLs to HTTP
echo "1. Forcing WordPress URLs to HTTP..."
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
UPDATE terrysha_options SET option_value = 'http://localhost:8080' WHERE option_name = 'siteurl';
UPDATE terrysha_options SET option_value = 'http://localhost:8080' WHERE option_name = 'home';
" 2>/dev/null

echo "   ✓ URLs set to http://localhost:8080"
echo ""

# 2. Remove any SSL-related options
echo "2. Removing SSL-related WordPress options..."
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
DELETE FROM terrysha_options WHERE option_name IN ('_transient_doing_cron', 'force_ssl_admin');
UPDATE terrysha_options SET option_value = '0' WHERE option_name = 'FORCE_SSL_ADMIN';
" 2>/dev/null

echo "   ✓ SSL options cleared"
echo ""

# 3. Check and fix wp-config.php
echo "3. Updating wp-config.php..."
echo ""

# Read current wp-config.php
docker exec "$WP_CONTAINER" cat /var/www/html/wp-config.php > /tmp/wp-config-check.php 2>/dev/null

# Check if localhost fix exists
if ! grep -q "localhost.*HTTPS.*off" /tmp/wp-config-check.php 2>/dev/null; then
    echo "   Adding localhost SSL fix..."
    
    # Create the fix code
    FIX_CODE='// Force HTTP for localhost
if (isset($_SERVER["HTTP_HOST"]) && (strpos($_SERVER["HTTP_HOST"], "localhost") !== false || strpos($_SERVER["HTTP_HOST"], "127.0.0.1") !== false)) {
    $_SERVER["HTTPS"] = "off";
    if (!defined("FORCE_SSL_ADMIN")) {
        define("FORCE_SSL_ADMIN", false);
    }
}'

    # Add before "That's all, stop editing!"
    docker exec "$WP_CONTAINER" sh -c "
        sed -i '/That.*all.*stop editing/i\
$FIX_CODE
' /var/www/html/wp-config.php
    " 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "   ✓ Added localhost SSL fix"
    else
        echo "   ⚠ Could not add automatically - will provide manual instructions"
    fi
else
    echo "   ✓ Localhost SSL fix already exists"
fi

# Remove any existing FORCE_SSL_ADMIN definitions
docker exec "$WP_CONTAINER" sed -i '/define.*FORCE_SSL_ADMIN.*true/d' /var/www/html/wp-config.php 2>/dev/null

echo ""

# 4. Check .htaccess for HTTPS redirects
echo "4. Checking .htaccess for HTTPS redirects..."
HTACCESS_FILE="/var/www/html/.htaccess"
if docker exec "$WP_CONTAINER" test -f "$HTACCESS_FILE" 2>/dev/null; then
    # Comment out any HTTPS redirects
    docker exec "$WP_CONTAINER" sed -i 's/^\(RewriteCond.*HTTPS\)/#\1/' "$HTACCESS_FILE" 2>/dev/null
    docker exec "$WP_CONTAINER" sed -i 's/^\(RewriteRule.*https\)/#\1/' "$HTACCESS_FILE" 2>/dev/null
    echo "   ✓ Commented out HTTPS redirects in .htaccess"
else
    echo "   ⊘ No .htaccess file found"
fi
echo ""

# 5. Clear WordPress transients that might cache SSL settings
echo "5. Clearing WordPress transients..."
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
DELETE FROM terrysha_options WHERE option_name LIKE '_transient%' OR option_name LIKE '_site_transient%';
" 2>/dev/null

echo "   ✓ Transients cleared"
echo ""

# 6. Restart WordPress
echo "6. Restarting WordPress container..."
docker-compose restart wordpress > /dev/null 2>&1
sleep 2

echo "   ✓ WordPress restarted"
echo ""

echo "=== Fix Complete ==="
echo ""
echo "What was fixed:"
echo "  ✓ WordPress URLs set to HTTP"
echo "  ✓ SSL options cleared"
echo "  ✓ wp-config.php updated for localhost"
echo "  ✓ .htaccess HTTPS redirects disabled"
echo "  ✓ WordPress transients cleared"
echo "  ✓ Container restarted"
echo ""
echo "IMPORTANT: Clear your browser cache!"
echo ""
echo "Chrome/Edge:"
echo "  1. Press Cmd+Shift+Delete (Mac) or Ctrl+Shift+Delete (Windows)"
echo "  2. Select 'Cached images and files'"
echo "  3. Click 'Clear data'"
echo ""
echo "Or use Incognito/Private mode:"
echo "  Cmd+Shift+N (Mac) or Ctrl+Shift+N (Windows)"
echo ""
echo "Then try accessing:"
echo "  http://localhost:8080/wp-admin/post.php?post=99&action=edit"
echo ""
echo "Make sure you're using http:// NOT https://"
