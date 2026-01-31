#!/bin/bash

# Script to update WordPress URLs in the database
# Usage: ./scripts/update-wordpress-urls.sh <old-url> <new-url>
# Example: ./scripts/update-wordpress-urls.sh terryshaguy.org localhost:8080

if [ -z "$1" ] || [ -z "$2" ]; then
    echo "Usage: $0 <old-url> <new-url>"
    echo "Example: $0 terryshaguy.org localhost:8080"
    exit 1
fi

OLD_URL="$1"
NEW_URL="$2"

CONTAINER_NAME="tsm-theme-db"
DB_NAME="wordpress"
DB_USER="wordpress"
DB_PASSWORD="wordpress"

echo "Updating WordPress URLs from '$OLD_URL' to '$NEW_URL'..."
echo ""

# Create SQL script with terrysha_ prefix
SQL_SCRIPT=$(cat <<EOF
UPDATE terrysha_options SET option_value = REPLACE(option_value, '$OLD_URL', '$NEW_URL') WHERE option_name IN ('siteurl', 'home');
UPDATE terrysha_posts SET post_content = REPLACE(post_content, '$OLD_URL', '$NEW_URL');
UPDATE terrysha_posts SET guid = REPLACE(guid, '$OLD_URL', '$NEW_URL');
UPDATE terrysha_postmeta SET meta_value = REPLACE(meta_value, '$OLD_URL', '$NEW_URL');
UPDATE terrysha_comments SET comment_content = REPLACE(comment_content, '$OLD_URL', '$NEW_URL');
UPDATE terrysha_comments SET comment_author_url = REPLACE(comment_author_url, '$OLD_URL', '$NEW_URL');
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
