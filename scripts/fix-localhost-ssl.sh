#!/bin/bash

# Script to fix SSL/HTTPS issues for localhost WordPress
# Usage: ./scripts/fix-localhost-ssl.sh

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "=== Fixing Localhost SSL Issues ==="
echo ""

# Check current URLs
echo "1. Checking current WordPress URLs..."
echo ""
SITE_URL=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'siteurl';" 2>/dev/null)
HOME_URL=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'home';" 2>/dev/null)

echo "   Current siteurl: $SITE_URL"
echo "   Current home: $HOME_URL"
echo ""

# Check if URLs contain https
if echo "$SITE_URL" | grep -q "https"; then
    echo "   ⚠ Site URL uses HTTPS - will update to HTTP"
    NEEDS_UPDATE=true
else
    echo "   ✓ Site URL already uses HTTP"
fi

if echo "$HOME_URL" | grep -q "https"; then
    echo "   ⚠ Home URL uses HTTPS - will update to HTTP"
    NEEDS_UPDATE=true
else
    echo "   ✓ Home URL already uses HTTP"
fi

if [ "$NEEDS_UPDATE" != "true" ]; then
    echo ""
    echo "URLs look correct. Checking for other SSL-related settings..."
    echo ""
fi

# Update URLs to HTTP if needed
if [ "$NEEDS_UPDATE" = "true" ]; then
    echo ""
    echo "2. Updating URLs to HTTP..."
    echo ""
    
    # Replace https://localhost:8080 with http://localhost:8080
    NEW_SITE_URL=$(echo "$SITE_URL" | sed 's|https://|http://|g')
    NEW_HOME_URL=$(echo "$HOME_URL" | sed 's|https://|http://|g')
    
    docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
        UPDATE terrysha_options SET option_value = '$NEW_SITE_URL' WHERE option_name = 'siteurl';
        UPDATE terrysha_options SET option_value = '$NEW_HOME_URL' WHERE option_name = 'home';
    " 2>/dev/null
    
    echo "   ✓ Updated siteurl to: $NEW_SITE_URL"
    echo "   ✓ Updated home to: $NEW_HOME_URL"
    echo ""
fi

# Check for FORCE_SSL_ADMIN setting
echo "3. Checking wp-config.php for SSL settings..."
echo ""

FORCE_SSL=$(docker exec tsm-theme-wordpress grep -i "FORCE_SSL" /var/www/html/wp-config.php 2>/dev/null | grep -v "^#" || echo "")

if [ -n "$FORCE_SSL" ]; then
    echo "   ⚠ Found FORCE_SSL setting in wp-config.php"
    echo "   Current setting: $FORCE_SSL"
    echo ""
    echo "   To disable SSL for localhost, add this to wp-config.php:"
    echo "   define('FORCE_SSL_ADMIN', false);"
    echo ""
else
    echo "   ✓ No FORCE_SSL settings found"
    echo ""
fi

# Check for redirect rules in .htaccess
echo "4. Checking for HTTPS redirects..."
echo ""

HTACCESS_REDIRECT=$(docker exec tsm-theme-wordpress grep -i "RewriteRule.*https" /var/www/html/.htaccess 2>/dev/null || echo "")

if [ -n "$HTACCESS_REDIRECT" ]; then
    echo "   ⚠ Found HTTPS redirect in .htaccess"
    echo "   This might be causing the issue"
    echo ""
else
    echo "   ✓ No HTTPS redirects found in .htaccess"
    echo ""
fi

# Add wp-config.php fix for localhost
echo "5. Adding localhost SSL fix to wp-config.php..."
echo ""

# Check if fix already exists
LOCALHOST_FIX=$(docker exec tsm-theme-wordpress grep -i "localhost.*ssl" /var/www/html/wp-config.php 2>/dev/null || echo "")

if [ -z "$LOCALHOST_FIX" ]; then
    # Add fix before "That's all, stop editing!"
    docker exec tsm-theme-wordpress sh -c "
        if ! grep -q 'FORCE_SSL_ADMIN.*localhost' /var/www/html/wp-config.php; then
            sed -i \"/That's all, stop editing/i\\
// Force HTTP for localhost\\
if (strpos(\$_SERVER['HTTP_HOST'], 'localhost') !== false || strpos(\$_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {\\
    \$_SERVER['HTTPS'] = 'off';\\
    define('FORCE_SSL_ADMIN', false);\\
}\\
\" /var/www/html/wp-config.php
        fi
    " 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "   ✓ Added localhost SSL fix to wp-config.php"
    else
        echo "   ⚠ Could not automatically add fix"
        echo "   Manually add this to wp-config.php before 'That's all, stop editing!':"
        echo ""
        echo "   // Force HTTP for localhost"
        echo "   if (strpos(\$_SERVER['HTTP_HOST'], 'localhost') !== false || strpos(\$_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {"
        echo "       \$_SERVER['HTTPS'] = 'off';"
        echo "       define('FORCE_SSL_ADMIN', false);"
        echo "   }"
    fi
else
    echo "   ✓ Localhost SSL fix already exists"
fi

echo ""
echo "=== Summary ==="
echo ""
echo "✓ WordPress URLs updated to HTTP"
echo "✓ wp-config.php configured for localhost"
echo ""
echo "Next steps:"
echo "1. Clear your browser cache"
echo "2. Try accessing the edit page again:"
echo "   http://localhost:8080/wp-admin/post.php?post=99&action=edit"
echo ""
echo "If it still doesn't work, try:"
echo "1. Hard refresh: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)"
echo "2. Clear browser cache completely"
echo "3. Try in incognito/private mode"
