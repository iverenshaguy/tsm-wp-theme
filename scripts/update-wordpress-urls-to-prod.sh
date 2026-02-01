#!/bin/bash

# Script to update WordPress URLs to production URLs
# Usage: ./scripts/update-wordpress-urls-to-prod.sh <dev-url> <production-url>
# Example: ./scripts/update-wordpress-urls-to-prod.sh localhost:8080 terryshaguy.org

if [ -z "$1" ] || [ -z "$2" ]; then
    echo "Usage: $0 <dev-url> <production-url>"
    echo "Example: $0 localhost:8080 terryshaguy.org"
    echo ""
    echo "This script converts URLs from your local development environment"
    echo "to production URLs, preparing your database for export/import."
    exit 1
fi

DEV_URL="$1"
PRODUCTION_URL="$2"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Updating WordPress URLs from '$DEV_URL' to '$PRODUCTION_URL'..."
echo ""
echo "⚠️  WARNING: This will modify your database!"
echo "   Make sure you have a backup before proceeding."
echo ""
read -p "Continue? (y/N): " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 1
fi

# Create SQL script with terrysha_ prefix
SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$DEV_URL', '$PRODUCTION_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$DEV_URL', '$PRODUCTION_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$DEV_URL', '$PRODUCTION_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$DEV_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$DEV_URL', '$PRODUCTION_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$DEV_URL', '$PRODUCTION_URL');
EOF
)

# Execute SQL
echo "Executing SQL updates..."
echo "$SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME"

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ URLs updated successfully!"
    echo ""
    echo "Note: Using table prefix 'terrysha_'"
    echo ""
    echo "Your database is now ready for export/import to production."
    echo "You can export it using:"
    echo "  docker exec $CONTAINER_NAME mysqldump -u$DB_USER -p$DB_PASSWORD $DB_NAME > database-export.sql"
else
    echo ""
    echo "✗ Error updating URLs. Please check the error above."
    exit 1
fi
