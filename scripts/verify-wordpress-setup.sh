#!/bin/bash

# Script to verify WordPress can see the database
# Usage: ./scripts/verify-wordpress-setup.sh

CONTAINER_NAME="tsm-theme-wordpress"
DB_CONTAINER="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "=== Verifying WordPress Setup ==="
echo ""

# Check wp-config.php in container
echo "1. Checking wp-config.php in container..."
echo ""
TABLE_PREFIX=$(docker exec "$CONTAINER_NAME" grep -E "^\$table_prefix" /var/www/html/wp-config.php 2>/dev/null | head -1)
echo "   Table prefix: $TABLE_PREFIX"
echo ""

DB_NAME_CONFIG=$(docker exec "$CONTAINER_NAME" grep -E "DB_NAME" /var/www/html/wp-config.php | grep -v "^#" | head -1)
DB_USER_CONFIG=$(docker exec "$CONTAINER_NAME" grep -E "DB_USER" /var/www/html/wp-config.php | grep -v "^#" | head -1)
DB_HOST_CONFIG=$(docker exec "$CONTAINER_NAME" grep -E "DB_HOST" /var/www/html/wp-config.php | grep -v "^#" | head -1)

echo "   Database name: $DB_NAME_CONFIG"
echo "   Database user: $DB_USER_CONFIG"
echo "   Database host: $DB_HOST_CONFIG"
echo ""

# Check if WordPress can connect
echo "2. Testing database connection from WordPress container..."
echo ""
docker exec "$CONTAINER_NAME" php -r "
define('DB_NAME', 'wordpress');
define('DB_USER', 'wordpress');
define('DB_PASSWORD', 'wordpress');
define('DB_HOST', 'db');
\$link = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (\$link) {
    echo '   ✓ Database connection successful\n';
    \$result = mysqli_query(\$link, 'SHOW TABLES LIKE \"terrysha_%\"');
    \$count = mysqli_num_rows(\$result);
    echo \"   ✓ Found \$count tables with terrysha_ prefix\n\";
    mysqli_close(\$link);
} else {
    echo '   ✗ Database connection failed: ' . mysqli_connect_error() . '\n';
}
" 2>/dev/null

echo ""
echo "3. Checking actual post counts in database..."
echo ""
POSTS=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'post' AND post_status = 'publish';" 2>/dev/null)
MISSIONS=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'mission' AND post_status = 'publish';" 2>/dev/null)
PAGES=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'page' AND post_status = 'publish';" 2>/dev/null)

echo "   Published posts: $POSTS"
echo "   Published missions: $MISSIONS"
echo "   Published pages: $PAGES"
echo ""

# Check WordPress installation status
echo "4. Checking WordPress installation status..."
echo ""
INSTALLED=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'users_can_register';" 2>/dev/null)

if [ -n "$INSTALLED" ]; then
    echo "   ✓ WordPress appears to be installed"
    echo ""
    echo "5. Checking WordPress version..."
    WP_VERSION=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'db_version';" 2>/dev/null)
    echo "   Database version: $WP_VERSION"
else
    echo "   ⚠ WordPress might not be fully installed"
fi

echo ""
echo "=== Summary ==="
echo ""
if [ -n "$POSTS" ] && [ "$POSTS" -gt 0 ] || [ -n "$MISSIONS" ] && [ "$MISSIONS" -gt 0 ]; then
    echo "✓ Data exists in database"
    echo "✓ WordPress should be able to see it"
    echo ""
    echo "If you still don't see data in WordPress admin:"
    echo "1. Clear browser cache"
    echo "2. Try logging out and back in"
    echo "3. Check: http://localhost:8080/wp-admin/edit.php?post_type=mission"
else
    echo "⚠ No published posts/missions found"
    echo "Check if data needs to be imported or if posts are in draft status"
fi
