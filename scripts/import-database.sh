#!/bin/bash

# Script to import a database dump into Docker MySQL container
# Usage: ./scripts/import-database.sh /path/to/your/database.sql

if [ -z "$1" ]; then
    echo "Usage: $0 <path-to-database.sql>"
    echo "Example: $0 ~/Downloads/mydatabase.sql"
    exit 1
fi

SQL_FILE="$1"

if [ ! -f "$SQL_FILE" ]; then
    echo "Error: File not found: $SQL_FILE"
    exit 1
fi

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Importing database from $SQL_FILE..."
echo "This may take a few minutes..."
echo ""

# Check if database exists, if not create it
echo "Ensuring database exists..."
docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;" 2>&1 | grep -v "Warning"

# Import the SQL file
echo "Importing SQL file..."
if docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < "$SQL_FILE" 2>&1; then
    echo ""
    echo "✓ Database import completed!"
    echo ""
    echo "Next steps:"
    echo "1. Check the import: ./scripts/check-database.sh"
    echo "2. Update URLs: ./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org localhost:8080"
    echo ""
    echo "Note: You may need to update wp-config.php in WordPress if table prefix differs."
else
    echo ""
    echo "✗ Import failed! Check the error above."
    echo ""
    echo "Common issues:"
    echo "- SQL file might be corrupted"
    echo "- Database connection failed"
    echo "- Insufficient permissions"
    exit 1
fi
echo ""
echo "Note: You may need to update wp-config.php or WordPress URLs if they differ."
echo "You can use WP-CLI or a search-replace plugin to update URLs."
