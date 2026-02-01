#!/bin/bash

# Script to remove wp_ tables after merging
# Usage: ./scripts/remove-wp-tables.sh [--confirm]

if [ "$1" != "--confirm" ]; then
    echo "⚠ WARNING: This will permanently delete all wp_* tables!"
    echo ""
    echo "Make sure you have:"
    echo "1. Verified the merge was successful"
    echo "2. Created a backup"
    echo "3. Tested WordPress thoroughly"
    echo ""
    echo "To proceed, run:"
    echo "  $0 --confirm"
    exit 1
fi

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Removing wp_ tables..."
echo ""

# Get list of wp_ tables
TABLES=$(docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -N -e "SHOW TABLES LIKE 'wp_%';" 2>/dev/null)

if [ -z "$TABLES" ]; then
    echo "No wp_ tables found. Nothing to remove."
    exit 0
fi

echo "Tables to remove:"
echo "$TABLES" | sed 's/^/  - /'
echo ""

# Remove tables
for table in $TABLES; do
    echo "Removing $table..."
    docker exec "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "DROP TABLE IF EXISTS \`$table\`;" 2>/dev/null
done

echo ""
echo "✓ All wp_ tables removed"
