#!/bin/bash

# Script to check database connection and verify imported data
# Usage: ./scripts/check-database.sh

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "=== Checking Database Connection ==="
echo ""

# Test connection
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" -e "SELECT 1;" "$DB_NAME" 2>&1

if [ $? -ne 0 ]; then
    echo ""
    echo "✗ Cannot connect to database!"
    echo "Make sure containers are running: docker-compose up -d"
    exit 1
fi

echo ""
echo "✓ Database connection successful!"
echo ""
echo "=== Checking Tables ==="
echo ""

# List all tables
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" -e "SHOW TABLES;" "$DB_NAME" 2>&1

echo ""
echo "=== Checking Table Prefix ==="
echo ""

# Check for terrysha_ prefix tables (suppress warnings)
TERRYSHA_COUNT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME' AND table_name LIKE 'terrysha_%';" 2>/dev/null)

echo "Tables with 'terrysha_' prefix: $TERRYSHA_COUNT"

if [ -z "$TERRYSHA_COUNT" ] || [ "$TERRYSHA_COUNT" -eq 0 ]; then
    echo ""
    echo "⚠ No tables found with 'terrysha_' prefix!"
    echo "Checking for 'wp_' prefix..."
    WP_COUNT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME' AND table_name LIKE 'wp_%';" 2>/dev/null)
    echo "Tables with 'wp_' prefix: $WP_COUNT"
fi

echo ""
echo "=== Checking Posts ==="
echo ""

# Check posts count (suppress warnings, specify database)
POSTS_COUNT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'post' AND post_status = 'publish';" 2>/dev/null)
echo "Published posts: $POSTS_COUNT"

MISSIONS_COUNT=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT COUNT(*) FROM terrysha_posts WHERE post_type = 'mission' AND post_status = 'publish';" 2>/dev/null)
echo "Published missions: $MISSIONS_COUNT"

echo ""
echo "=== Checking WordPress Options ==="
echo ""

# Check site URL (suppress warnings)
SITE_URL=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'siteurl';" 2>/dev/null)
HOME_URL=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SELECT option_value FROM terrysha_options WHERE option_name = 'home';" 2>/dev/null)

echo "Site URL: $SITE_URL"
echo "Home URL: $HOME_URL"

echo ""
echo "=== Summary ==="
echo ""
echo "If you see 0 posts/missions, the import may have failed or used wrong table prefix."
echo "If URLs are still pointing to production, run: ./scripts/update-wordpress-urls.sh"
