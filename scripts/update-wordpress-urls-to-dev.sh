#!/bin/bash

# Script to update WordPress URLs to development/local URLs
# Usage: ./scripts/update-wordpress-urls-to-dev.sh <production-url> <dev-url>
# Example: ./scripts/update-wordpress-urls-to-dev.sh terryshaguy.org localhost:8080

if [ -z "$1" ] || [ -z "$2" ]; then
    echo "Usage: $0 <production-url> <dev-url>"
    echo "Example: $0 terryshaguy.org localhost:8080"
    exit 1
fi

PRODUCTION_URL="$1"
DEV_URL="$2"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Updating WordPress URLs from '$PRODUCTION_URL' to '$DEV_URL'..."
echo ""

# Create SQL script with terrysha_ prefix
SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$PRODUCTION_URL', '$DEV_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$PRODUCTION_URL', '$DEV_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$PRODUCTION_URL', '$DEV_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$PRODUCTION_URL', '$DEV_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$PRODUCTION_URL', '$DEV_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$PRODUCTION_URL', '$DEV_URL');
EOF
)

# Execute SQL
echo "$SQL_SCRIPT" | docker exec -i "$CONTAINER_NAME" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME"

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ URLs updated successfully!"
    echo ""
    echo "Note: Using table prefix 'terrysha_'"
else
    echo ""
    echo "✗ Error updating URLs. Please check the error above."
    exit 1
fi
